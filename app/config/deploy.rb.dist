set :application,           "api.regidium.com"
set :domain,                "api.regidium.com"
set :deploy_to,             "/var/www/regidium.com/api"
set :app_path,              "app"
set :web_path,              "web"

set :repository,            "git@github.com:regidium/api.git"
set :branch,                "master"
set :scm,                   :git
set :deploy_via,            :copy

role :web,        domain
role :app,        domain, :primary => true

default_run_options[:pty] = true

set :keep_releases,         3
set :ssh_options,           {:forward_agent => true, :port => 22}
set :user,                  "root"
set :use_sudo,              true
set :use_composer,          true
set :update_vendors,        true

set :shared_files,          [
                                "app/config/parameters.yml"
                            ]

set :shared_children,       [
                                app_path + "/cache",
                                app_path + "/logs",
                                web_path + "/bundles",
                                "vendor",
                                "config"
                            ]