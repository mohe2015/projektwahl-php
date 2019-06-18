CREATE OR REPLACE FUNCTION check_choices_grade() RETURNS TRIGGER AS
$$
BEGIN
IF (SELECT min_grade FROM projects WHERE id = NEW.project) > (SELECT grade FROM users WHERE id = NEW.student) OR (SELECT max_grade FROM projects WHERE id = NEW.project) < (SELECT grade FROM users WHERE id = NEW.student) THEN
RAISE EXCEPTION 'Der Schüler passt nicht in die Altersbegrenzung des Projekts!';
END IF;
RETURN NEW;
END;
$$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trigger_check_choices_grade ON choices;
CREATE TRIGGER trigger_check_choices_grade
BEFORE INSERT ON choices
FOR EACH ROW EXECUTE FUNCTION check_choices_grade();




CREATE OR REPLACE FUNCTION update_project_check_choices_grade() RETURNS TRIGGER AS
$$
BEGIN
IF (SELECT COUNT(*) FROM choices, users WHERE choices.project = NEW.id AND users.id = choices.student AND (users.grade < NEW.min_grade OR users.grade > NEW.max_grade)) > 0 THEN
RAISE EXCEPTION 'Geänderte Altersbegrenzung würde Wahlen ungültig machen!';
END IF;
RETURN NEW;
END;
$$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trigger_update_project_check_choices_grade ON projects;
CREATE TRIGGER trigger_update_project_check_choices_grade
BEFORE UPDATE ON projects
FOR EACH ROW EXECUTE FUNCTION update_project_check_choices_grade();
