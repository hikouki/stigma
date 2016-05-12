# stigma
[![PHP Status](https://img.shields.io/badge/php->= 5.6-orange.svg)](http://php.net)
[![Build Status](https://travis-ci.org/hikouki/stigma.svg?branch=master)](https://travis-ci.org/hikouki/stigma)
[![Coverage Status](https://coveralls.io/repos/github/hikouki/stigma/badge.svg?branch=master)](https://coveralls.io/github/hikouki/stigma?branch=master)

Search replace tool for wordpress database.(sqlite, ...)

# Install

```bash
composer global require hikouki/stigma
```

# Usage

```
          __  .__
  _______/  |_|__| ____   _____ _____
 /  ___/\   __\  |/ ___\ /     \\__  \
 \___ \  |  | |  / /_/  >  Y Y  \/ __ \_
/____  > |__| |__\___  /|__|_|  (____  /
     \/         /_____/       \/     \/
```

Usage:  
  php stigma <database_file_path> <target> <replace>

Example:  
  php stigma ./ht.sqlite localhost 160.122.111.11

# License

MIT
