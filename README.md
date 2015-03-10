#A simple portal for onsite Elections

##Why use paper when you have a computer?

##Setup

1. Create mysql Database.
2. Run `CRE.sql` to setup database.
3. Update `config.php` with required settings.

##Features

- Simple, easy-to-use interface for voters.
- Again, simple, easy-to-use interface for admins.
- Can be configured to allow voting for multiple candidates (For example, you have to vote for exactly 2 candidates).
- Can be configured to allow negative votes (They are always optional).
- Allow access only from designated clients.
- Multiple client support.
- Admin has to allow each and every vote from each client to prevent from abuse.

##Note

The default admin password is `password`

To change password, change value of `admin_pass` to `sha1('yourpassword')` in `meta` table. (You have to be real admin to do this :P)
