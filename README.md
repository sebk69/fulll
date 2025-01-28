# Hiring test response

## Algo exercise

### Run

```shell
cd algo
bin/run [N]
```

The program run FizBuz algorithm from 1 to N numbers

### Test

```shell
cd algo
bin/composer unit-tests
```

## Backend exercise

### Fleet command

The fleet command build docker containers if docker command is installed on your pc else, you must have php 8.3 installed with swoole 5.x.

Here are example commands :
```shell
./fleet create me
```

```shell
./fleet register vehicle abc 01JJN4330GCQPWP3D8Z49EBNCW 
```

```shell
./fleet register vehicle abc 01JJN4330GCQPWP3D8Z49EBNCW 
```

```shell
./fleet lcoalize-vehicle 01JJN4330GCQPWP3D8Z49EBNCW abc 0.5646546516 0.6546454465448 146
```

### Test

```shell
cd backoffice
bin/composer behat
```

### Quality

After each modification, run :
```shell
bin/composer phpstan 
```

In order to keep code quality.