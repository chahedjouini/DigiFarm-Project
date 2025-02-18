<?php
namespace App\Enum;

enum EnumStatutCommande: string
{
   case EN_COURS = 'en_cours';
   case VALIDEE = 'validee';
   case LIVREE = 'livree';
   case ANNULEE = 'annulee';
 }




// namespace App\Enum;

// enum EnumStatutCommande: string
// {
//     case EN_COURS = 'en_cours';
//     case TERMINE = 'termine';
//     case ANNULE = 'annule';

//     public static function getValues(): array
//     {
//         return [
//             self::EN_COURS->value,
//             self::TERMINE->value,
//             self::ANNULE->value,
//         ];
//     }
// }


