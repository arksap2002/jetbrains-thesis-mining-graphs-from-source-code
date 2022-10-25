# Mining graphs from source code test task
## Solution
### Description
### Dataset
I took a dataset from top [100 php projects](https://github.com/ozh/top_100_PHP_projects).
### Files
```
main.php – php parser. argv[1] is the name of a parsing file. Put data in data.txt
processPhpFiles.sh – running the parser with all current php files (instead of main.php)
repositories.txt – names of php repositories
run.sh – applying processPhpFiles.sh to all repositories
data.zip – zip of data.txt and data.ods files
data.txt – output data
data.ods – sheet with data
```
## Conclusion
I got a `data.txt` file with data of functions in all projects, which I put in `LibreOffice Calc` – `data.ods` and got different results:
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
run.sh
```