set :application, "Proyecto Flor"
set :domain, "lovenlock.com"
set :deploy_to, "/kunden/homepages/7/d553154757/htdocs"
set :app_path, "app"
 
set :repository, "git@bitbucket.org:nivalwebteam/proyectoflor.git"
set :scm, :git
 
set :model_manager, "doctrine"
 
role :web, domain
role :app, domain, :primary => true
 
set :use_sudo, false
set :user, "u79123358"
 
set  :keep_releases, 3
 
set :dump_assetic_assets, true
set :use_composer, true
 
set :shared_files, ["app/config/parameters.yml"]
set :shared_children, [app_path + "/logs", app_path + "/upload", "vendor", app_path + "/sessions"]
 
set :writable_dirs, ["app/cache", "app/logs", "app/sessions"]
set :webserver_user, "www-data"
set :permission_method, :acl
set :use_set_permissions, true
 
ssh_options[:forward_agent] = true
default_run_options[:pty] = true