set :application,           "api.regidium.com"
set :domain,                "api.regidium.com"
set :deploy_to,             "/var/www/regidium.com/api"

set :scm,                   :git
set :repository,            "git@github.com:regidium/api.git"
set :branch,                "master"
set :deploy_via, :remote_cache

role :app,                  domain, :primary => true

default_run_options[:pty] = true

set :keep_releases,         3
set :ssh_options,           {:forward_agent => true, :port => 22}
set :user,                  "deployer"
set :use_sudo,              false
set :use_composer,          true
set :update_vendors,        false
set :vendors_mode,          "install"

set :shared_files,          [
                                "app/config/parameters.yml",
                                "app/config/db/db.yml",
                                "app/config/redis/redis.yml",
                                "app/config/security.yml",
                            ]


set :shared_children,       [
                                "app/cache",
                                "app/logs",
                                "web/bundles",
                                "vendor"
                            ]

set :writable_dirs,         [
                                "app/cache",
                                "app/logs"
                            ]

set :webserver_user,        "www-data"
set :permission_method,     :acl
#set :use_set_permissions,   true

logger.level = Logger::MAX_LEVEL
