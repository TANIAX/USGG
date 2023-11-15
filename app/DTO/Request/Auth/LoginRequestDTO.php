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
     * Constructeur de la classe - initialise le contenu du DTO
     *
     * @return void
     */
    public function __construct($json = null)
    {
         parent::__construct($json);
    }
        
    /**
     * Méthode de validation du DTO
     *
     * @return iterable message d'erreurs
     */
    public function validate() : iterable
    {
        $this->errors = [];
        $this->violations[] = $this->validator->validate($this->email, [
            new Email(['message' => 'L\'adresse email n\'est pas valide'])
        ]); 

        $this->violations[] = $this->validator->validate($this->password, [
            new Length(['min' => 8, 'minMessage'=> 'Le mot de passe doit contenir au moins 8 caractères']),
            new Length(['max' => 16, 'maxMessage'=> 'Le mot de passe doit contenir au maximum 16 caractères']),

        ]); 

        foreach($this->violations as $violation)
        {
            if(count($violation) > 0)
            {
                foreach($violation as $error)
                    $this->errors[] = $error->getMessage();
            }
        }

        return $this->errors;
    }
}