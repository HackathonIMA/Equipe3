#!bin/bash

git subtree push --prefix app/backend/ heroku master

heroku run rake db:reset
heroku run rake db:migrate
