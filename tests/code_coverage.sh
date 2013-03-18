#!/bin/bash
mkdir -p ./code_coverage/
phpunit --bootstrap ./bootstrap.php --coverage-html ./code_coverage/ ./
