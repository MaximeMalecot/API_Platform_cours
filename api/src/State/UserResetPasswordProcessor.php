<?php

namespace App\State;

use App\Entity\User;
use App\Dto\UserResetPasswordDto;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserResetPasswordProcessor implements ProcessorInterface
{

    public function __construct(private UserResetPasswordDto $dto, private EntityManagerInterface $em, private UserPasswordHasherInterface $encoder){}

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$data instanceof UserResetPasswordDto) {
            return;
        }
        $user = $this->em->getRepository(User::class)->findOneBy(['resetPwdToken' => $data->token]);
        if(!$user){
            throw new NotFoundHttpException('User not found');
        }
        $user->setPassword($this->encoder->hashPassword($user, $data->password));
        $user->setResetPwdToken(null);
        $this->em->flush();
        return $user;
    }
}