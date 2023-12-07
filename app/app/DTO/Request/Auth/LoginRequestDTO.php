<?php

namespace App\DTO\Request\Auth;

use App\DTO\Request\DTORequest;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @author: Guillaume cornez
 */
class LoginRequestDTO extends DTORequest
{
    public string $email = '';
    public string $password = '';

    /**
     * Constructor of the DTO
     *
     * @return void
     */
    public function __construct($json = null)
    {
         parent::__construct($json);
    }
        
    /**
     * Validate the DTO
     *
     * @return iterable errors messages
     */
    public function validate() : iterable
    {
        $this->errors = [];
        $this->violations[] = $this->validator->validate($this->email, [
            new Email(['message' => 'L\'adresse email n\'est pas valide'])
        ]); 

        $this->violations[] = $this->validator->validate($this->password, [
            new Length(['min' => 8, 'minMessage'=> 'Le mot de passe doit contenir au moins 8 caractères']),
            new Length(['max' => 32, 'maxMessage'=> 'Le mot de passe doit contenir au maximum 32 caractères']),

        ]); 

        return $this->getErrors();
    }
}