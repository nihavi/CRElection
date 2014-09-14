#A simple portal for onsite Elections

##Why use paper when you have a computer?

##Setup

1. Create mysql Database.
2. Run `CRE.sql` to setup database.
3. Update `config.php` with required settings.

##Note

The default admin password is `password`

To change password, change value of `admin_pass` to `sha1('yourpassword')` in `meta` table. (You have to be real admin to do this :P)
