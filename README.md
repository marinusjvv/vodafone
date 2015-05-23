This project was created in order to complete a technical test for Vodafone. You
can clone it using the following command: 

```
git clone git@github.com:marinusjvv/vodafone.git
```

You will then have to run the following command in the project's main folder, in
order to download it's dependancies:

```
composer install
```

The script is run commandline, and requires a csv file which contains all the
connections and their latencies (in milliseconds)

In order to run the script run the following command:

```
php <script base directory path>/index.php <path to input csv file>
```

You will then be prompted to input desired to and from device, as well as the
time limit in milliseconds. Format should be [Device From] [Device To] [Time]
You will then be given the path and the time taken to travel that path.

I chose not to map out all the paths from startup of the program, as some paths
may later become invalid. An example of this is if a device gets disconnected.
The paths are therefore mapped on request.