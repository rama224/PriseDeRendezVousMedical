<?php

/*
|--------------------------------------------------------------------------
| Messages de validation (règles utilisées par l'application)
|--------------------------------------------------------------------------
*/

return [

    'after' => 'Le champ :attribute doit être une date postérieure au :date.',
    'after_or_equal' => 'Le champ :attribute doit être une date postérieure ou égale au :date.',
    'confirmed' => 'La confirmation du champ :attribute ne correspond pas.',
    'date' => "Le champ :attribute n'est pas une date valide.",
    'date_format' => 'Le champ :attribute ne correspond pas au format :format.',
    'email' => 'Le champ :attribute doit être une adresse e-mail valide.',
    'exists' => 'Le champ :attribute sélectionné est invalide.',
    'integer' => 'Le champ :attribute doit être un entier.',
    'max' => [
        'string' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
    ],
    'min' => [
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'required' => 'Le champ :attribute est obligatoire.',
    'string' => 'Le champ :attribute doit être une chaîne de caractères.',
    'unique' => 'Cette valeur du champ :attribute est déjà utilisée.',

    'attributes' => [
        'name' => 'nom',
        'email' => 'adresse e-mail',
        'password' => 'mot de passe',
        'centre_id' => 'centre',
        'specialite_id' => 'spécialité',
        'medecin_id' => 'médecin',
        'date' => 'date',
        'date_heure' => 'créneau',
        'motif' => 'motif',
    ],

];
