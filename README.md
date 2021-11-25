# Installation
1. composer req
2. php artisan vendor:publish --provider="Tmkzmu\Fortress\FortressServiceProvider"
3. Add `VerifyEmailTrait` trait to user if email verification needed
4. Implement `MustVerifyEmail`
5. Set features
6. Run migrations
