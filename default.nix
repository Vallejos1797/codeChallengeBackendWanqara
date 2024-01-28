{ pkgs ? import <nixpkgs> {} }:

pkgs.mkShell {
  buildInputs = with pkgs; [
    php80
    composer
    git
  ];

  shellHook = ''
    export COMPOSER_HOME=$TMPDIR
    composer create-project laravel/laravel:^4.5.1 my-laravel-app --prefer-dist --no-progress --no-interaction
    cd my-laravel-app
    php artisan key:generate
  '';
}
