CREATE EXTENSION dblink;

INSERT INTO projects (id, title, info, place, costs, min_grade, max_grade, min_participants, max_participants, presentation_type, requirements, random_assignments)
SELECT id, title, info, place, costs, min_grade, max_grade, min_participants, max_participants, presentation_type, helper_count, random_assignment_allowed FROM dblink('dbname=projektwahl_production', 'SELECT id, title, info, place, costs, min_grade, max_grade, min_participants, max_participants, presentation_type, helper_count, random_assignment_allowed FROM projects') AS t1(id INT, title TEXT, info TEXT, place TEXT, costs DECIMAL, min_grade INT, max_grade INT, min_participants INT, max_participants INT, presentation_type TEXT, helper_count TEXT, random_assignment_allowed BOOLEAN);

INSERT INTO users (id, name, password, type, project_leader, class, grade, away)
SELECT id, name, password, 'student', project_leader, class, grade, away FROM dblink('dbname=projektwahl_production', 'SELECT
id, name, password_digest, clazz, grade, is_away, project_leader_id FROM users WHERE type = ''Student''') AS t1(id INT,
name TEXT, password TEXT, class TEXT, grade INT, away BOOL, project_leader INT);

INSERT INTO choices (rank, project, student)
SELECT rank, project, student FROM dblink('dbname=projektwahl_production', 'SELECT "order", project_id, student_id FROM ratings') AS t1(rank INT, project INT, student INT);
