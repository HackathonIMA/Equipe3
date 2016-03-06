#!bin/bash

echo "Dropping connections..."
psql postgres -c "SELECT pg_terminate_backend(pg_stat_activity.pid)
FROM pg_stat_activity
WHERE pg_stat_activity.datname = 'falae_development'
  AND pid <> pg_backend_pid();"

echo "rake db:drop"
rake db:drop
echo "rake db:create"
rake db:create
echo "rake db:migrate"
rake db:migrate
echo "rake db:seed"
rake db:seed
