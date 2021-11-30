<?php

namespace Idharf\PhpMvc\Service;

use Exception;
use Idharf\PhpMvc\Config\Database;
use Idharf\PhpMvc\Exception\ValidationException;
use Idharf\PhpMvc\Model\UserRegisterRequest;
use Idharf\PhpMvc\Model\UserRegisterResponse;
use Idharf\PhpMvc\Repository\UserRepository;
use Idharf\PhpMvc\Domain\User;
use Idharf\PhpMvc\Model\UpdatePasswordRequest;
use Idharf\PhpMvc\Model\UpdatePasswordResponse;
use Idharf\PhpMvc\Model\UpdateProfileRequest;
use Idharf\PhpMvc\Model\UpdateProfileResponse;
use Idharf\PhpMvc\Model\UserLoginRequest;
use Idharf\PhpMvc\Model\UserLoginResponse;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        $this->validateUserRegistrationRequest($request);
        try {
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);
            if ($user != null) {
                throw new ValidationException("User sudah ada!");
            }

            $user = new User();
            $user->id = $request->id;
            $user->name = $request->name;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
            $this->userRepository->save($user);

            $response = new UserRegisterResponse();
            $response->user = $user;
            Database::commitTransaction();

            return $response;
        } catch (Exception $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    private function validateUserRegistrationRequest(UserRegisterRequest $request)
    {
        if (
            empty($request->id) ||
            empty($request->name) ||
            empty($request->password)
        ) {
            throw new ValidationException("Semua isian wajib tidak boleh kosong!");
        }
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validateUserLogin($request);
        $user = $this->userRepository->findById($request->id);
        if ($user == null) {
            throw new ValidationException("id atau password salah");
        }

        if (password_verify($request->password, $user->password)) {
            $response = new UserLoginResponse();
            $response->user = $user;
            return $response;
        } else {
            throw new ValidationException("id atau password salah");
        }
    }

    private function validateUserLogin(UserLoginRequest $request)
    {
        if (
            empty($request->id) ||
            empty($request->password)
        ) {
            throw new ValidationException("id atau password tidak boleh kosong");
        }
    }

    public function updateProfile(UpdateProfileRequest $request): UpdateProfileResponse
    {
        $this->validateUpdateProfile($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);
            if ($user == null) {
                throw new ValidationException("User tidak ditemukan");
            }

            $user->name = $request->name;
            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UpdateProfileResponse();
            $response->user = $user;

            return $response;
        } catch (Exception $ex) {
            Database::rollbackTransaction();
            throw new ValidationException("Error Bos: " . $ex);
        }
    }

    private function validateUpdateProfile(UpdateProfileRequest $request)
    {
        if (
            empty($request->id) ||
            empty($request->name)
        ) {
            throw new ValidationException("Name tidak boleh kosong");
        }
    }

    public function updatePassword(UpdatePasswordRequest $request): UpdatePasswordResponse
    {

        $this->validateUpdatePassword($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);
            if ($user == null) {
                throw new ValidationException("User tidak ditemukan");
            }

            if (!password_verify($request->oldPassword, $user->password)) {
                throw new ValidationException("Password lama tidak sesuai");
            }

            $user->password = password_hash($request->newPassword, PASSWORD_BCRYPT);
            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UpdatePasswordResponse();
            $response->user = $user;

            return $response;
        } catch (Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateUpdatePassword(UpdatePasswordRequest $request)
    {
        if (
            empty($request->id) ||
            empty($request->newPassword) ||
            empty($request->oldPassword)
        ) {
            throw new ValidationException("Password lama atau baru tidak boleh kosong");
        }
    }
}
