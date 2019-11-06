set :application, 'learning-cms'
set :repo_url, 'https://github.com/strawbees/learning-cms.git'

# Branch options
# Prompts for the branch name (defaults to current branch)
#ask :branch, -> { `git rev-parse --abbrev-ref HEAD`.chomp }
set :branch, ENV.fetch('REVISION', -> { `git rev-parse --abbrev-ref HEAD`.chomp })

set :deploy_to, -> { "/opt/bitnami/apps/#{fetch(:application)}/htdocs" }

# Use :debug for more verbose output when troubleshooting
set :log_level, :info

# Apache users with .htaccess files:
# it needs to be added to linked_files so it persists across deploys:
# set :linked_files, fetch(:linked_files, []).push('.env', 'web/.htaccess')
set :linked_files, fetch(:linked_files, []).push('.env')
set :linked_dirs, fetch(:linked_dirs, []).push('web/app/uploads')

namespace :deploy do
  desc 'Change permissions'
  task :change_permissions do
    on roles(:app) do
      within fetch(:release_path) do
        execute :chmod, '777', 'web/app/cache'
        execute :chmod, '777', 'web/app/w3tc-config'
      end
    end
  end
end
after 'deploy:publishing', 'deploy:change_permissions'

namespace :deploy do
  desc 'Restart application'
  task :restart do
    on roles(:app), in: :sequence, wait: 5 do
      sudo '/opt/bitnami/ctlscript.sh', 'restart', 'nginx'
    end
  end
end
after 'deploy:publishing', 'deploy:restart'

namespace :deploy do
  desc 'Update WordPress template root paths to point to the new release'
  task :update_option_paths do
    on roles(:app) do
      within fetch(:release_path) do
        if test :wp, :core, 'is-installed'
          [:stylesheet_root, :template_root].each do |option|
            # Only change the value if it's an absolute path
            # i.e. The relative path "/themes" must remain unchanged
            # Also, the option might not be set, in which case we leave it like that
            value = capture :wp, :option, :get, option, raise_on_non_zero_exit: false
            if value != '' && value != '/themes'
              execute :wp, :option, :set, option, fetch(:release_path).join('web/wp/wp-content/themes')
            end
          end
        end
      end
    end
  end
end

# The above update_option_paths task is not run by default
# Note that you need to have WP-CLI installed on your server
# Uncomment the following line to run it on deploys if needed
after 'deploy:publishing', 'deploy:update_option_paths'
