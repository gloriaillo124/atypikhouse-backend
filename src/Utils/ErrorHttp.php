<?php

namespace App\Utils;

class ErrorHttp
{
    public const ERROR = ['message'=> 'error', 'codeHttp'=> 200];
    public const FORM_ERROR = ['message'=> 'Formulaire invalid', 'codeHttp'=> 200];
    public const USER_ERROR = ['message'=> 'Adresse email invalid', 'codeHttp'=> 200];

    public const USER_DISABLE_ERROR = ['message'=> 'Votre compte est désactivé', 'codeHttp'=> 200];
    public const PASSWORD_ERROR = ['message'=> 'Mot de passe invalid', 'codeHttp'=> 200];

    public const ID_ERROR = ['message'=> 'Erreur de mise à jour', 'codeHttp'=> 200];

    public const ID_REQUESTE_ERROR = ['message'=> 'Erreur de requette ou mauvaise manipulation', 'codeHttp'=> 200];

    public const ID_DELETE_ERROR = ['message'=> 'Erreur de suppression', 'codeHttp'=> 200];
}