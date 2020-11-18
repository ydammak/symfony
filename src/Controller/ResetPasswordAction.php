<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResetPasswordAction
{
    private $validator;
    private $userPasswordEncoder;
    private $entityManager;
    private $tokenManger;
    public function __construct(ValidatorInterface $validator,UserPasswordEncoderInterface $userPasswordEncoder,
        EntityManagerInterface $entityManager,JWTTokenManagerInterface $tokenManger )
    {
        $this->validator=$validator;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->entityManager = $entityManager;
        $this->tokenManger = $tokenManger;

    }

    public function __invoke(User $data)
    {
        //var_dump($data->getNewPassword(), $data->getNewRetypedPassword(),$data->getOldPassword());
        //die;
        $this->validator->validate($data);
        $data->setPassword($this->userPasswordEncoder->encodePassword($data,$data->getNewPassword()));
        $this->entityManager->flush();
        $token= $this->tokenManger->create($data);
        
        return new JsonResponse(['token'=> $token]);
    }  
}