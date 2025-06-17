# run.py

import os
from app import create_app

# default to development configuration when FLASK_CONFIG is not set
config_name = os.getenv('FLASK_CONFIG', 'development')
app = create_app(config_name)


if __name__ == '__main__':
	app.run()
