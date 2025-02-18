<?php
namespace App\Enum;

enum EnumTypeProduit: string
{
    case ACHAT = 'Achat';
    case VENTE = 'Vente';

}
// namespace App\Enum;

// class EnumTypeProduit
// {
//     private $value;

//     public function __construct($value)
//     {
//         $this->value = $value;
//     }

//     public function __toString()
//     {
//         return (string) $this->value;
//     }
// }


// namespace App\Enum;

// enum EnumTypeProduit: string
// {
//     case ACHAT = 'Achat';
//     case VENTE = 'Vente';

//     public static function getValues(): array
//     {
//         return [
//             self::ACHAT->value,
//             self::VENTE->value,
//         ];
//     }
// }


