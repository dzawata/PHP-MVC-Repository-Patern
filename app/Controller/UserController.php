<?php

namespace Idharf\PhpMvc\Controller;

use Exception;
use Idharf\PhpMvc\App\View;
use Idharf\PhpMvc\Config\Database;
use Idharf\PhpMvc\Exception\ValidationException;
use Idharf\PhpMvc\Model\UpdatePasswordRequest;
use Idharf\PhpMvc\Model\UpdateProfileRequest;
use Idharf\PhpMvc\Model\UserLoginRequest;
use Idharf\PhpMvc\Model\UserRegisterRequest;
use Idharf\PhpMvc\Repository\SessionRepository;
use Idharf\PhpMvc\Repository\UserRepository;
use Idharf\PhpMvc\Service\SessionService;
use Idharf\PhpMvc\Service\UserService;

class UserController
{
    private $userService;
    private $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);

        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function login()
    {
        View::render('User/login', [
            'title' => 'Login',
            'id' => '',
            'password' => ''
        ]);
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->id = $_POST['id'];
        $request->password = $_POST['password'];
        
        try{
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->id);
            View::redirect('/');
        }catch(ValidationException $exception){
            View::render('User/login', [
                'title' => 'Login',
                'id' => $_POST['id'],
                'password' => $_POST['password'],
                'error' => 'Id atau password salah'
            ]); 
        }
    }

    public function logout()
    {
        $this->sessionService->destroy();
        View::redirect('/');
    }
    
    public function register()
    {
        View::render('User/register', [
            'title' => 'Register User',
            'id' => '',
            'name' => ''
        ]);
    }

    public function postRegister()
    {
        $request = new UserRegisterRequest();
        $request->id = $_POST['id'];
        $request->name = $_POST['name'];
        $request->password = $_POST['password'];

        try{
            $this->userService->register($request);
            $params = [
                'title' => 'Register',
                'id' => '',
                'name' => '',
                'password' => '',
                'success' => 'Berhasil register'
            ];
        }catch(ValidationException $exception){
            $params = [
                'title' => 'Register User',
                'id' => $request->id,
                'name' => $request->name,
                'error' => $exception->getMessage()
            ];
        }
        View::render('User/register', $params);
    }

    public function updateProfile()
    {
        $user = $this->sessionService->current();
        View::render("User/profile", [
            'id' => $user->id,
            'name' => $user->name
        ]);
    }

    public function postUpdateProfile()
    {
        $user = $this->sessionService->current();
        $request = new UpdateProfileRequest();
        $request->id = $user->id;
        $request->name = $_POST['name'];

        try{
            $this->userService->updateProfile($request);
            View::redirect('/');
        }catch(ValidationException $exception){
            View::render("User/profile", [
                'title' => 'Update Profile',
                'error' => 'Error bos : ' . $exception->getMessage(),
                'id' => $user->id,
                'name' => $_POST['name']
            ]);
        }
    }

    public function updatePassword()
    {
        $user = $this->sessionService->current();
        View::render("User/ubah_password", [
            'title' => 'Ubah Password',
            'id' => $user->id
        ]);
    }

    public function postUpdatePassword(){

        $user = $this->sessionService->current();

        $request = new UpdatePasswordRequest();
        $request->id = $user->id;
        $request->newPassword = $_POST['newPassword'];
        $request->oldPassword = $_POST['oldPassword'];

        try{
            $this->userService->updatePassword($request);
            View::redirect('/');
        }catch(Exception $exception){
            View::render("User/ubah_password", [
                'title' => 'Ubah Password',
                'error' => $exception->getMessage(),
                'id' => $user->id
            ]);
        }
    }
}