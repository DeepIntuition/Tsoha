-- Luodaan kolme luokkaa
INSERT INTO Class (name)
    VALUES ('Maximum Flow');

INSERT INTO Class (name)
    VALUES ('Sorting Algorithm');

INSERT INTO Class (name)
    VALUES ('Linear Algebra');

-- Luodaan yksi ylläpitäjä ja yksi tavallinen käyttäjä
INSERT INTO Contributor (name, password, administrator)
    VALUES ('Yyni Yllapitaja', '$5$riP20hvAnf_28$aQaGgR/6nHsT7ku/sP6e7BZnrUU93Qhm3RMbkV025G4', TRUE);

-- Luodaan 4 uutta algoritmia
INSERT INTO Algorithm (class_id, name, timecomplexity, year, author, description)
    VALUES ((SELECT id FROM Class WHERE name='Maximum Flow'), 
    		'Ford Fulkerson',
    		'O(Ef)',
    		'1956',
    		'Ford and Fulkerson',
    		'Ford-Fulkerson Algorithm (FFA) is a residual flow algoritm in flow graph networks.');

INSERT INTO Algorithm (class_id, name, timecomplexity, year, author, description)
    VALUES ((SELECT id FROM Class WHERE name='Sorting Algorithm'), 
    		'Mergesort',
    		'O(nlogn)',
    		'1945',
    		'John von Neumann',
    		'Mergesort is a general-purpose sorting algorithm invented by John van Neumann in 1945');

INSERT INTO Algorithm (class_id, name, timecomplexity, year, author, description)
    VALUES ((SELECT id FROM Class WHERE name='Linear Algebra'), 
    		'Strassen Matrix Multiplication',
    		'O(n^2.8047)',
    		'1969',
    		'Voler Strassen',
    		'In linear algebra, the Strassen algorithm, named after Volker Strassen, is an algorithm for matrix multiplication. It is faster than the standard matrix multiplication algorithm and is useful in practice for large matrices, but would be slower than the fastest known algorithms for extremely large matrices. Strassens algorithm works for any ring, such as plus/multiply, but not all semirings, such as min/plus or boolean algebra, where the naive algorithm still works, and so called combinatorial matrix multiplication.');

INSERT INTO Algorithm (class_id, name, timecomplexity, year, author, description)
    VALUES ((SELECT id FROM Class WHERE name='Sorting Algorithm'), 
    		'Heap Sort',
    		'O(nlogn)',
    		'1964',
    		'W.J. Williams',
    		'In computer science, heapsort is a comparison-based sorting algorithm. Heapsort can be thought of as an improved selection sort: like that algorithm, it divides its input into a sorted and an unsorted region, and it iteratively shrinks the unsorted region by extracting the largest element and moving that to the sorted region. The improvement consists of the use of a heap data structure rather than a linear-time search to find the maximum.[2] Although somewhat slower in practice on most machines than a well-implemented quicksort, it has the advantage of a more favorable worst-case O(n log n) runtime. Heapsort is an in-place algorithm, but it is not a stable sort.');

-- Luodaan tagi
INSERT INTO Tag (name)
	VALUES ('Sortable');

INSERT INTO Tag (name)
	VALUES ('efficient');

INSERT INTO Tag (name)
	VALUES ('Greedy');

INSERT INTO Tag (name)
	VALUES ('general-purpose');

-- Liitetään tageja algoritmiin taboject
INSERT INTO Tagobject (algorithm_id, tag_id)
	VALUES ((SELECT id FROM Algorithm WHERE name='Mergesort'), (SELECT id FROM Tag WHERE name='Sortable'));

INSERT INTO Tagobject (algorithm_id, tag_id)
	VALUES ((SELECT id FROM Algorithm WHERE name='Heap Sort'), (SELECT id FROM Tag WHERE name='general-purpose'));

-- Liitetään algoritmeja algoritmeihin
INSERT INTO Algorithmlink (algorithmfrom_id, algorithmto_id)
	VALUES ((SELECT id FROM Algorithm WHERE name='Heap Sort'), (SELECT id FROM Algorithm WHERE name='Mergesort'));

INSERT INTO Algorithmlink (algorithmfrom_id, algorithmto_id)
	VALUES ((SELECT id FROM Algorithm WHERE name='Linear Algebra'), (SELECT id FROM Algorithm WHERE name='Maximum Flow'));

-- Lisätään NikkiSaari56:n versio Pythonilla toteutetusta Ford-Fulkerson-algoritmista
INSERT INTO Implementation (algorithm_id, contributor_id, programminglanguage, date, description)
	VALUES ((SELECT id FROM Algorithm WHERE name='Ford Fulkerson'), 
			(SELECT id FROM Contributor WHERE name='NikkiSaari56'),
			'Python',
			'2012-01-08',
			'xxxcccxx cccxxx fjeifjeifje jfeifjeifje fjeifjsöflk kfdölfk');

INSERT INTO Implementation (algorithm_id, contributor_id, programminglanguage, date, description)
	VALUES ((SELECT id FROM Algorithm WHERE name='Strassen Matrix Multiplication'), 
			(SELECT id FROM Contributor WHERE name='NikkiSaari56'),
			'Java',
			'2003-03-07',
			'for i in n, for j in k, where n is the horizontal and k is the vertical dimensionality of the matrix...');


-- Lisätään NikkiSaari56:n analyysi Mergesortista
INSERT INTO Analysis (algorithm_id, contributor_id, timecomplexity, date, description)
	VALUES ((SELECT id FROM Algorithm WHERE name='Mergesort'), 
			(SELECT id FROM Contributor WHERE name='NikkiSaari56'),
			'O(nlogn)',
			'2012-01-08',
			'Mergesort can be analyzed through substitution method, we can derive a good guess through basic recursion tree method: at each level of recursion we do about n amount of work and at each level the array is split into two. We will have a guess of O(nlogn). We have to show that T(n) <= cnlogn. The basic recursion looks like the following: T(n)=2T(n/2) + n. By substitution we get: T(n) = 2(c*n/2*log*(n/2)) <= cn*(logn - log2) <= cnlogn');

INSERT INTO Analysis (algorithm_id, contributor_id, timecomplexity, date, description)
	VALUES ((SELECT id FROM Algorithm WHERE name='Mergesort'), 
			(SELECT id FROM Contributor WHERE name='NikkiSaari56'),
			'O(nlogn)',
			'2012-01-08',
			'Mergesort can be analyzed through substitution method, we can derive a good guess through basic recursion tree method: at each level of recursion we do about n amount of work and at each level the array is split into two. We will have a guess of O(nlogn). We have to show that T(n) <= cnlogn. The basic recursion looks like the following: T(n)=2T(n/2) + n. By substitution we get: T(n) = 2(c*n/2*log*(n/2)) <= cn*(logn - log2) <= cnlogn');

