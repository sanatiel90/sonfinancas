<?php

//arq que vai dar rollback nas migracoes, fazer nova migracao e popular a tabela com seeds; func exec() executa um comando do terminal

exec(__DIR__ . '/vendor/bin/phinx rollback -t=0');
exec(__DIR__ . '/vendor/bin/phinx migrate');
exec(__DIR__ . '/vendor/bin/phinx seed:run');