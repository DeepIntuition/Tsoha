CREATE TABLE Class(
  id SERIAL PRIMARY KEY, 
  name varchar(50) NOT NULL
);

CREATE TABLE Algorithm(
  id SERIAL PRIMARY KEY,
  class_id INTEGER REFERENCES Class(id), -- Viiteavain Class-tauluun
  name varchar(120) UNIQUE NOT NULL,
  timecomplexity varchar(30) NOT NULL,
  year varchar(4),
  author varchar(120),
  description varchar(4000) NOT NULL
);

CREATE TABLE Algorithmlink(
  id SERIAL PRIMARY KEY,
  algorithmfrom_id INTEGER REFERENCES Algorithm(id),
  algorithmto_id INTEGER REFERENCES Algorithm(id)
);

CREATE TABLE Contributor(
  id SERIAL PRIMARY KEY,
  name varchar(15) UNIQUE NOT NULL,
  password varchar(30) NOT NULL,
  administrator boolean DEFAULT FALSE
);

CREATE TABLE Implementation(
  id SERIAL PRIMARY KEY,
  algorithm_id INTEGER REFERENCES Algorithm(id),
  contributor_id INTEGER REFERENCES Contributor(id),
  programminglanguage varchar(30) NOT NULL,
  date date NOT NULL,
  description varchar(2000) NOT NULL
);

CREATE TABLE Analysis(
  id SERIAL PRIMARY KEY,
  algorithm_id INTEGER REFERENCES Algorithm(id),
  contributor_id INTEGER REFERENCES Contributor(id),
  timecomplexity varchar(30) NOT NULL,
  date date NOT NULL,
  description varchar(4000) NOT NULL
);

CREATE TABLE Tag(
  id SERIAL PRIMARY KEY,
  name varchar(30) UNIQUE NOT NULL
);

CREATE TABLE Tagobject(
  id SERIAL PRIMARY KEY,
  algorithm_id INTEGER REFERENCES Algorithm(id),
  tag_id INTEGER REFERENCES Tag(id)
);