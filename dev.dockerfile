FROM vectorweb/research-coder-api

# The shared volume inherits the permissions (including uid / gid) of the
# user running the container, so owner of the files in the container will
# be the id of the user on the host system (not root:www-data). Upon not
# finding a satisfactory solution, this workaround will set the group id
# of www-data in the container to the host user's group id.
RUN usermod -u ${USER_UID:-1000} www-data && \
    groupmod -g ${USER_GID:-1000} www-data

# The host filesystem is assumed to have the correct file permissions.
# Specifically, g+r for everything and g+w forstorage/ and bootstrap/cache/.

# See the helper script (docker-dev.sh) bundled with this repo to have this
# automated.
