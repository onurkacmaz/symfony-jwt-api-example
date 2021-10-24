# Installation

- 1 - composer install 
- 2 - edit env database settings
- 3 - bin/console doctrine:database:create
- 4 - bin/console doctrine:schema:update --force
- 5 - php bin/console doctrine:migrations:migrate	
- 6 - create new user in user table
- 7 - bin/console security:hash-password and copy hash
- 8 - define the password to the user you created
- 9 - php bin/console lexik:jwt:generate-keypair
- 9 - symfony server:start

