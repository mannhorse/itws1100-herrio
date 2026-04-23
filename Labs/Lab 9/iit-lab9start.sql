-- create the tables for our movies
CREATE TABLE `movies` (
   `movieid` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `title` varchar(100) NOT NULL,
   `year` char(4) DEFAULT NULL,
   PRIMARY KEY (`movieid`)
);

-- insert data into the movies table
INSERT INTO movies
VALUES (1, "Elizabeth", "1998"),
   (2, "Black Widow", "2021"),
   (3, "Oh Brother Where Art Thou?", "2000"),
   (4, "The Lord of the Rings: The Fellowship of the Ring", "2001"),
   (5, "Up in the Air", "2009");

-- create the actors table
CREATE TABLE `actors` (
   `actorid` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `last_name` varchar(40) NOT NULL,
   `first_names` varchar(60) NOT NULL,
   `dob` date DEFAULT NULL,
   PRIMARY KEY (`actorid`)
);

-- insert actors into the actors table
-- at least 3 must have birthdates prior to 1/1/1960
INSERT INTO actors (`last_name`, `first_names`, `dob`)
VALUES
   ("Blanchett", "Cate", "1969-05-14"),
   ("Johansson", "Scarlett", "1984-11-22"),
   ("Clooney", "George", "1961-05-06"),
   ("Pitt", "Brad", "1963-12-18"),
   ("Newman", "Paul", "1925-01-26"),
   ("Hepburn", "Audrey", "1929-05-04"),
   ("Eastwood", "Clint", "1930-05-31"),
   ("Streep", "Meryl", "1949-06-22"),
   ("Hanks", "Tom", "1956-07-09"),
   ("Freeman", "Morgan", "1937-06-01"),
   ("Washington", "Denzel", "1954-12-28"),
   ("Hopkins", "Anthony", "1937-12-31"),
   ("DiCaprio", "Leonardo", "1974-11-11"),
   ("Lawrence", "Jennifer", "1990-08-15");

-- create the movie_actors relationship table
CREATE TABLE `movie_actors` (
   `movieid` int(10) unsigned NOT NULL,
   `actorid` int(10) unsigned NOT NULL,
   PRIMARY KEY (`movieid`, `actorid`),
   FOREIGN KEY (`movieid`) REFERENCES `movies`(`movieid`),
   FOREIGN KEY (`actorid`) REFERENCES `actors`(`actorid`)
);

-- associate actors with movies
INSERT INTO movie_actors (`movieid`, `actorid`)
VALUES
   (1, 1),
   (2, 2),
   (3, 3),
   (4, 1),
   (5, 3);
