{ pkgs ? import <nixpkgs> {} }:

pkgs.mkShell {
  buildInputs = with pkgs; [
    php
    composer
    git
  ];

  shellHook = ''
    export COMPOSER_HOME=$TMPDIR
  '';
}
