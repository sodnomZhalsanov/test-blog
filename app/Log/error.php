
Unsuccessful query processing. SQLSTATE[42703]: Undefined column: 7 ERROR:  column "cart_id" does not exist
LINE 10:                    ) ON CONFLICT (cart_id, product_id) DO UP...
                                          ^
HINT:  Perhaps you meant to reference the column "basket_cards.card_id". in /var/www/html/app/Repository/BasketCardRepository.php on 110 line.
Unsuccessful query processing. SQLSTATE[42703]: Undefined column: 7 ERROR:  column "product_id" does not exist
LINE 10:                    ) ON CONFLICT (card_id, product_id) DO UP...
                                          ^ in /var/www/html/app/Repository/BasketCardRepository.php on 110 line.
Unsuccessful query processing. SQLSTATE[42P10]: Invalid column reference: 7 ERROR:  there is no unique or exclusion constraint matching the ON CONFLICT specification in /var/www/html/app/Repository/BasketCardRepository.php on 110 line.
Unsuccessful query processing. SQLSTATE[42P10]: Invalid column reference: 7 ERROR:  there is no unique or exclusion constraint matching the ON CONFLICT specification in /var/www/html/app/Repository/BasketCardRepository.php on 110 line.
