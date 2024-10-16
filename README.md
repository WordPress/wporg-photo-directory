# Photo Directory

The codebase and development environment for the WordPress.org Photo Directory.

## Development

### Prerequisites

* Docker
* Node/npm
* Composer

### Setup

1. Set up repo dependencies.

	```bash
	npm run setup:tools
	```

1. Build the project.

	```bash
	npm run build --workspaces
	```

1. Start the local environment.

	```bash
	npm run wp-env start
	```

1. Run the setup script.

	```bash
	npm run setup:wp
	```

1. (optional) There may be times when you want to make changes to the Parent theme and test them with the Main them. To do that:
	1. Clone the Parent repo and follow the setup instructions in its `readme.md` file.
	1. Create a `.wp-env.override.json` file in this repo
	1. Copy the `themes` section from `.wp-env.json` and paste it into the override file. You must copy the entire section for it to work, because it won't be merged with `.wp-env.json`.
	1. Update the path to the Parent theme to the Parent theme folder inside the Parent repository you cloned above.

	```json
	{
		"themes": [
			"./source/wp-content/themes/wporg",
			"./source/wp-content/themes/wporg-photos",
			"./source/wp-content/themes/wporg-photos-2024"
			"../wporg-parent-2021/source/wp-content/themes/wporg-parent-2021"
		]
	}
	```

1. Visit site at [localhost:8888](http://localhost:8888).

1. Log in with username `admin` and password `password`.

### Environment management

These must be run in the project's root folder, _not_ in theme/plugin subfolders.

* Stop the environment.

	```bash
	npm run wp-env stop
	```

* Restart the environment.

	```bash
	npm run wp-env start
	```

* Refresh local WordPress content with a current copy from the staging site.

	```bash
	npm run setup:refresh
	```

* Reset WordPress to a clean install, and reconfigure. This will remove all local WordPress content!

	```bash
	npm run wp-env clean all
	npm run setup:wp
	```

* SSH into docker container.

	```bash
	npm run wp-env run wordpress bash
	```

* Run wp-cli commands. Use the `--` separator to pass flags through to wp-cli.

	```bash
	npm run wp-env run cli wp post list -- --post_status=publish
	```

* Update composer dependencies and sync any `repo-tools` changes.

	```bash
	npm run update:tools
	```
