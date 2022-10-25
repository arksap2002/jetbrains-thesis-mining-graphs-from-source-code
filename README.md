# Mining graphs from source code test task
## Solution
### Description
I found different options for parsing php files. Usually the best way to do it is converting a code to an AST tree, but for this task I decided to split a code to different tokens using [PhpToken::tokenize](https://www.php.net/manual/en/phptoken.tokenize.php) command. This list of tokens is really comfortable to find data of functions – walk around `T_FUNCTION` tokens.\
The schema of my solution is iterating through the list of the repositories and for each project do operations: cloning, processing and removing.\
I have a php script that parses php code and puts data in the `data.txt` file, bash script for running php script with all php files and another bash script for working with repositories.
### Data parameters
```
* name
* modifier
* comment
* number of arguments
* size (lines)
* number of if statements
* number of for statements
* number of while statements
```
### Input dataset
I took a dataset from [top 100 php projects](https://github.com/ozh/top_100_PHP_projects).
### Files
`main.php` – php parser. `argv[1]` is the name of a parsing file\
`processPhpFiles.sh` – running the parser with all current php files (instead of `main.php`)\
`repositories.txt` – names of php repositories\
`run.sh` – applying `processPhpFiles.sh` to all repositories\
`data.zip` – zip of `data.txt` and `data.ods` files\
`data.txt` – output data\
`data.ods` – sheet with data
## Conclusion
I generated a `data.txt` file with the data of functions in all projects, which I put in `LibreOffice Calc` – `data.ods` and got different results:
```
percent of anonymous functions ≈ 0.02
percent of public ≈ 0.72
percent of protected ≈  0.1
percent of private ≈ 0.07
average size (number of lines) ≈ 28
```
## Run
To run, you need to have PHP 8, because I used the [PhpToken::tokenize](https://www.php.net/manual/en/phptoken.tokenize.php) command.\
Running time ≈ 30 min.\
Be careful, the biggest project size is 264.8 MB.
```
./run.sh
```
