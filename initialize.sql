
CREATE TABLE PostalCodes (
  PostalCode CHAR(7) PRIMARY KEY,
  City VARCHAR(255),
  Province VARCHAR(255)
);

CREATE TABLE Facilities (
  FacilityID INT PRIMARY KEY,
  Name VARCHAR(255),
  Type VARCHAR(255),
  Capacity INT,
  WebAddress VARCHAR(255),
  PhoneNumber VARCHAR(255),
  Address VARCHAR(255),
  PostalCode VARCHAR(255),
  FOREIGN KEY (PostalCode) REFERENCES PostalCodes(PostalCode),
  CHECK (Type in ('Hospital', 'CLSC', 'Clinic', 'Pharmacy', 'Special instalment'))
);

CREATE TABLE Employees (
  EmployeeID INT PRIMARY KEY,
  FName VARCHAR(255),
  LName VARCHAR(255),
  Role VARCHAR(255),
  DoBirth DATE,
  MedicareNumber VARCHAR(255) NOT NULL UNIQUE,
  Email VARCHAR(255),
  Citizenship VARCHAR(255),
  PhoneNumber VARCHAR(255),
  Address VARCHAR(255),
  PostalCode VARCHAR(255),
  FOREIGN KEY (PostalCode) REFERENCES PostalCodes(PostalCode),
  CHECK (Role IN ('Nurse', 'Doctor', 'Cashier', 'Pharmacist', 'Receptionist', 'Administrative personnel', 'Security personnel', 'Regular employee'))
);

CREATE TABLE Managers (
  EmployeeID INT PRIMARY KEY,
  FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID)
);

CREATE TABLE Vaccines (
  EmployeeID INT,
  FacilityID INT,
  VaccineID INT NOT NULL,
  Type VARCHAR(255),
  DoseNumber INT,
  Date DATE,
  PRIMARY KEY (EmployeeID, FacilityID, VaccineID),
  FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID),
  FOREIGN KEY (FacilityID) REFERENCES Facilities(FacilityID)
);

CREATE TABLE Infections (
  EmployeeID INT,
  InfectionID INT NOT NULL,
  Type VARCHAR(255),
  Date DATE,
  PRIMARY KEY (EmployeeID, InfectionID),
  FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID)
);

CREATE TABLE Employment (
  FacilityID INT,
  EmployeeID INT,
  ContractID INT,
  StartDate DATE NOT NULL,
  EndDate DATE,
  PRIMARY KEY (FacilityID, EmployeeID, ContractID),
  FOREIGN KEY (FacilityID) REFERENCES Facilities(FacilityID),
  FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID),
  CHECK (COALESCE(EndDate, '9999-12-31') > StartDate)
);

CREATE TABLE Managing (
  FacilityID INT,
  EmployeeID INT,
  StartDate DATE NOT NULL,
  EndDate DATE,
  PRIMARY KEY (FacilityID, EmployeeID, StartDate),
  FOREIGN KEY (FacilityID) REFERENCES Facilities(FacilityID),
  FOREIGN KEY (EmployeeID) REFERENCES Managers(EmployeeID),
  CHECK (COALESCE(EndDate, '9999-12-31') > StartDate)
);

CREATE TABLE EmailLog (
   FacilityID INT,
   EmployeeID INT,
   Date DATE,
   Subject VARCHAR(255),
   Body TEXT,
   PRIMARY KEY (FacilityID, EmployeeID, Date),
   FOREIGN KEY (FacilityID) REFERENCES Facilities(FacilityID),
   FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID)
);

CREATE TABLE Schedule (
   FacilityID INT,
   EmployeeID INT,
   Date DATE,
   StartTime TIME,
   EndTime TIME,
   PRIMARY KEY (FacilityID, EmployeeID, Date, StartTime),
   FOREIGN KEY (FacilityID) REFERENCES Facilities(FacilityID),
   FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID),
   CHECK (EndTime >= StartTime)
);

DELIMITER $$
CREATE EVENT SendWeeklySchedule
ON SCHEDULE EVERY 1 WEEK
-- STARTS CURRENT_DATE + INTERVAL 6 - WEEKDAY(CURRENT_DATE) DAY
STARTS '2023-04-09 22:53:00'
DO
BEGIN
    DECLARE ScheduleDate DATE;
    DECLARE StartTime TIME;
    DECLARE EndTime TIME;
    DECLARE EmployeeID_local INT;
    DECLARE EmployeeFirstName VARCHAR(50);
    DECLARE EmployeeLastName VARCHAR(50);
    DECLARE EmployeeEmail VARCHAR(50);
    DECLARE FacilityID_local INT;
    DECLARE FacilityName VARCHAR(50);
    DECLARE FacilityAddress VARCHAR(100);
    DECLARE EmailSubject VARCHAR(100);
    DECLARE EmailBody VARCHAR(1000);
    DECLARE EmailDate DATE DEFAULT CURDATE();
    DECLARE currentDate DATE;
	DECLARE count INT DEFAULT 0;
    DECLARE done INT DEFAULT FALSE;
    DECLARE cur CURSOR FOR 
    SELECT DISTINCT EmployeeID, FacilityID
		FROM Schedule s
		WHERE s.Date BETWEEN DATE_ADD(CURDATE(), INTERVAL 1 DAY) AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
		ORDER BY EmployeeID, FacilityID;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    
	CREATE VIEW ScheduleView AS
    SELECT s.Date, s.StartTime, s.EndTime, s.EmployeeID, e.FName, e.LName, e.Email, f.FacilityID, f.Name, f.Address
        FROM Schedule s
        JOIN Employees e ON s.EmployeeID = e.EmployeeID
        JOIN Facilities f ON s.FacilityID = f.FacilityID
        WHERE s.Date BETWEEN DATE_ADD(CURDATE(), INTERVAL 1 DAY) AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
        ORDER BY EmployeeID, FacilityID, Date;

   -- Loop through schedule records
    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO EmployeeID_local, FacilityID_local;
        
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        SET FacilityName = (SELECT DISTINCT sv.Name FROM ScheduleView sv WHERE sv.FacilityID = FacilityID_local);
        SET EmailSubject = CONCAT(FacilityName, ' Schedule from Monday ', DATE_ADD(CURDATE(), INTERVAL 1 DAY), ' to Sunday ', DATE_ADD(CURDATE(), INTERVAL 7 DAY));
        
        SET EmployeeFirstName = (SELECT DISTINCT sv.FName FROM ScheduleView sv WHERE sv.EmployeeID = EmployeeID_local);
        SET EmployeeLastName = (SELECT DISTINCT sv.LName FROM ScheduleView sv WHERE sv.EmployeeID = EmployeeID_local);
        SET FacilityAddress = (SELECT DISTINCT sv.Address FROM ScheduleView sv WHERE sv.FacilityID = FacilityID_local);
        SET EmailBody = CONCAT('Dear ', EmployeeFirstName, ' ', EmployeeLastName, ',\n\n', 
                               'Here is your schedule: \n\n', 
                               'Facility: ', FacilityName, '\n', 
                               'Address: ', FacilityAddress, '\n');

		SET currentDate = DATE_ADD(CURDATE(), INTERVAL 1 DAY);		
		WHILE currentDate != DATE_ADD(CURDATE(), INTERVAL 8 DAY) DO
			
			CREATE TEMPORARY TABLE daySchedule AS
            SELECT sv.StartTime, sv.EndTime 
				FROM ScheduleView sv 
                WHERE sv.EmployeeID = EmployeeID_local AND sv.FacilityID = FacilityID_local AND sv.Date = currentDate;

            SET count = (SELECT COUNT(*) FROM daySchedule);
            
            IF count = 0 THEN
				SET EmailBody = CONCAT(EmailBody, DAYNAME(currentDate), ' No Assignment.\n');
			ELSE
				SET StartTime = (SELECT sv.StartTime FROM ScheduleView sv WHERE sv.EmployeeID = EmployeeID_local AND sv.FacilityID = FacilityID_local AND sv.Date = currentDate);
				SET EndTime = (SELECT sv.EndTime FROM ScheduleView sv WHERE sv.EmployeeID = EmployeeID_local AND sv.FacilityID = FacilityID_local AND sv.Date = currentDate);
				SET EmailBody = CONCAT(EmailBody, DAYNAME(currentDate), ' From ', StartTime, ' To ', EndTime, '.\n');
            END IF;
            
            DROP TABLE daySchedule;
            SET currentDate = DATE_ADD(currentDate, INTERVAL 1 DAY);
        END WHILE;
        
        SET EmailBody = CONCAT(EmailBody, 
                               'Please let us know if you have any questions or concerns.\n\n', 
                               'Best regards,\n', 
                               'The HR team');
        -- CALL SendEmail(EmployeeEmail, EmailSubject, EmailBody);
        -- Insert email into the email log
        INSERT INTO EmailLog 
        VALUES (FacilityID_local, EmployeeID_local, EmailDate, EmailSubject, LEFT(EmailBody, 80));
    END LOOP;
    CLOSE cur; 
    
	DROP VIEW ScheduleView;
END;$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER AddingEmployeeExceedCapacityFacility
BEFORE INSERT ON Employment
FOR EACH ROW
BEGIN
    DECLARE CapacityOfFacility INT;
    DECLARE CurrentCount INT;
    SET CapacityOfFacility  = (SELECT Capacity FROM Facilities WHERE FacilityID = NEW.FacilityID);
    SET CurrentCount = (SELECT COUNT(EmployeeID) FROM Employment WHERE FacilityID = NEW.FacilityID);
    
    IF (CurrentCount >= CapacityOfFacility) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot assign this employee to the facility. The facility has reached its maximum capacity.';
    END IF;
END;$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER CheckScheduleConflict 
BEFORE INSERT ON Schedule 
FOR EACH ROW 
BEGIN 
  IF EXISTS (SELECT * FROM Schedule 
             WHERE EmployeeID = NEW.EmployeeID AND
			 Date = NEW.Date AND
			 ((StartTime <= NEW.StartTime AND EndTime >= NEW.StartTime) OR 
                     		 (StartTime >= NEW.StartTime AND StartTime <= NEW.EndTime))) 
  THEN 
SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Employee is scheduled at a conflicting time'; 
  END IF; 
END;$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER CheckTimeIntervalSchedule BEFORE INSERT ON Schedule
FOR EACH ROW
BEGIN
  IF EXISTS (SELECT * FROM Schedule
             WHERE EmployeeId = NEW.EmployeeId
             AND Date = NEW.Date
             AND ((StartTime <= NEW.StartTime AND EndTime > NEW.StartTime - INTERVAL 1 HOUR)
                  OR (StartTime >= New.StartTime AND StartTime < NEW.EndTime + INTERVAL 1 HOUR))) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Employee is scheduled with less than 1 hour interval';
  END IF;
END;$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER FourWeekSchedule
BEFORE INSERT ON Schedule
FOR EACH ROW
BEGIN
  IF NEW.Date > (NOW() + INTERVAL 4 WEEK) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Cannot schedule more than four weeks ahead of time.';
  END IF;
END;$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER CancelAssignments
AFTER INSERT ON Infections
FOR EACH ROW
BEGIN
    DECLARE EmpType VARCHAR(255);
    DECLARE InfectedDate DATE;
    -- Get employee type and latest infected date from the newly inserted row
    SET EmpType = (SELECT e.Role FROM Employees e WHERE e.EmployeeID = NEW.EmployeeID);
    SET InfectedDate = (
        SELECT MAX(i.Date) 
        FROM Infections i 
        WHERE i.EmployeeID = NEW.EmployeeID AND i.Type = 'COVID-19'
    );
    -- Check if the infected employee is a doctor or a nurse
    IF EmpType IN ('Nurse', 'Doctor') AND InfectedDate IS NOT NULL THEN
        -- Cancel all assignments for the infected employee for two weeks
        DELETE 
        FROM Schedule 
        WHERE EmployeeID = NEW.EmployeeID AND 
              Date >= InfectedDate AND 
              Date < DATE_ADD(InfectedDate, INTERVAL 14 DAY);
    END IF;
END; $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER EmailWarningInfectedEmployee
AFTER INSERT ON Infections
FOR EACH ROW
BEGIN
    DECLARE InfectedEmpType VARCHAR(255);
    DECLARE InfectedDate DATE;
    -- Get employee type and latest infected date from the newly inserted row
    SET InfectedEmpType = (SELECT e.Role FROM Employees e WHERE e.EmployeeID = NEW.EmployeeID);
    SET InfectedDate = (
        SELECT MAX(i.Date) 
        FROM Infections i 
        WHERE i.EmployeeID = NEW.EmployeeID AND i.Type = 'COVID-19'
    );
    -- Check if the infected employee is a doctor or a nurse
    IF InfectedEmpType IN ('Nurse', 'Doctor') AND InfectedDate IS NOT NULL THEN
    
        -- Email warning to inform/track all the doctors and nurses who have been in contact by having the same schedule as the infected employee
		INSERT INTO EmailLog (FacilityID, EmployeeID, Date, Subject, Body)
        SELECT DISTINCT FacilityID, s.EmployeeID, NEW.Date AS Date, 'Warning' AS Subject, 'One of your colleagues that you have worked with in the past two weeks have been infected with COVID-19' AS Body
		FROM Schedule s, Employees e
		WHERE s.EmployeeID = e.EmployeeID AND
			  (s.FacilityID IN (SELECT FacilityID FROM Employment em WHERE (em.EmployeeID = NEW.EmployeeID))) AND
			   s.EmployeeID <> NEW.EmployeeID AND
			   e.Role IN ('Nurse', 'Doctor') AND
			   s.Date IN (SELECT Date FROM Schedule WHERE (EmployeeID = NEW.EmployeeID)) AND
               s.Date <= InfectedDate AND 
               s.Date >= DATE_SUB(InfectedDate, INTERVAL 14 DAY) AND
			   s.StartTime = ANY (SELECT StartTime FROM Schedule WHERE (EmployeeID = NEW.EmployeeID AND Date IN (SELECT Date FROM Schedule WHERE (EmployeeID = NEW.EmployeeID)))) AND 
               s.EndTime =  ANY (SELECT EndTime FROM Schedule WHERE (EmployeeID = NEW.EmployeeID AND Date IN (SELECT Date FROM Schedule WHERE (EmployeeID = NEW.EmployeeID))));
    END IF;
END; $$
DELIMITER ;

INSERT INTO PostalCodes (PostalCode, City, Province) VALUES
('H3G 1B3', 'Montreal', 'Quebec'),
('H3G 1Z7', 'Montreal', 'Quebec'),
('H3G 1A1', 'Montreal', 'Quebec'),
('H3G 1R5', 'Montreal', 'Quebec'),
('H3G 2S6', 'Montreal', 'Quebec'),
('H3G 2C2', 'Montreal', 'Quebec'),
('H3G 1J5', 'Montreal', 'Quebec'),
('H3G 1Y2', 'Montreal', 'Quebec'),
('H3G 2G8', 'Montreal', 'Quebec'),
('H3G 1G3', 'Montreal', 'Quebec'),
('X3Y 6S8', 'Toronto', 'Ontario'),
('L2C 4T9', 'Toronto', 'Ontario'),
('H3G 1A7', 'Montreal', 'Quebec'),
('J4X 9T2', 'Brossard', 'Quebec'),
('J5X 4T6', 'Brossard', 'Quebec'),
('N5T 2IE', 'Toronto', 'Ontario'),
('J5T 8G4', 'Toronto', 'Ontario'),
('H3S 1W8', 'Montreal', 'Quebec'),
('H4V 1B8', 'Montreal', 'Quebec'),
('H3J 1C7', 'Montreal', 'Quebec'),
('H6A 1B4', 'Montreal', 'Quebec'),
('H8D 1F3', 'Montreal', 'Quebec'),
('H3S 1W7', 'Montreal', 'Quebec'),
('H4T 1C3', 'Montreal', 'Quebec'),
('H6J 8P5', 'Montreal', 'Quebec'),
('H5G 3S5', 'Montreal', 'Quebec'),
('H9D 5B5', 'Montreal', 'Quebec'),
('G1B 0A9', 'Quebec', 'Quebec'),
('H4T 2X5', 'Montreal', 'Quebec');

INSERT INTO Facilities (FacilityID, Name, Type, Capacity, WebAddress, PhoneNumber, Address, PostalCode) VALUES
(1, 'Hospital Maisonneuve Rosemont', 'Hospital', 500, 'www.centralhospital.com', '514-555-1234', '123 Main Street', 'H3G 1B3'),
(2, 'North CLSC', 'CLSC', 200, 'www.northCLSC.com', '514-555-2345', '456 Queen Street', 'H3G 1Z7'),
(3, 'West Pharmacy', 'Pharmacy', 300, 'www.westpharmacy.com', '514-555-3456', '789 King Street', 'H3G 1A1'),
(4, 'East Hospital', 'Hospital', 400, 'www.easthospital.com', '514-555-4567', '246 College Street', 'H3G 1R5'),
(5, 'South Special instalment', 'Special instalment', 250, 'www.south.com', '514-555-5678', '369 Bathurst Street', 'H3G 2S6'),
(6, 'Midtown Hospital', 'Hospital', 450, 'www.midtownhospital.com', '514-555-6789', '159 Spadina Avenue', 'H3G 2C2'),
(7, 'Downtown CLSC', 'CLSC', 175, 'www.downtownCLSC.com', '514-555-7890', '425 Yonge Street', 'H3G 1J5'),
(8, 'Uptown Pharmacy', 'Pharmacy', 325, 'www.uptownpharmacy.com', '514-555-8901', '520 Bloor Street', 'H3G 1Y2'),
(9, 'Harbourfront Hospital', 'Hospital', 375, 'www.harbourfronthospital.com', '514-555-9012', '235 Queens Quay West', 'H3G 2G8'),
(10, 'West End Special instalment', 'Special instalment', 225, 'www.westend.com', '514-555-0123', '867 Queen Street West', 'H3G 1G3'),
(11, 'General Hospital', 'Hospital', 200, 'www.generalhospital.com', '520-333-1333', '203 Saint-Peter Street', 'X3Y 6S8'),
(12, 'Queen West CLSC', 'CLSC', 100, 'www.queenwestclsc.com', '520-333-9897', '1000 Saint-Louis Street', 'L2C 4T9'),
(13, 'Downtown Hospital', 'Hospital', 5, 'www.downtownhospital.com', '514-383-9696', '1000 Saint-Antoine Street', 'H4T 2X5');

INSERT INTO Employees (EmployeeID, FName, LName, Role, DoBirth, MedicareNumber, Email, Citizenship, PhoneNumber, Address, PostalCode) VALUES
(1, 'John', 'Doe', 'Nurse', '1980-01-01', '123-456-789', 'johndoe@email.com', 'Canadian', '514-555-1234', '123 Main Street', 'H3G 1A7'),
(2, 'Jane', 'Smith', 'Doctor', '1982-02-15', '234-567-890', 'janesmith@email.com', 'Canadian', '514-555-2345', '456 Queen Street', 'H3G 1A7'),
(3, 'Bob', 'Johnson', 'Cashier', '1985-03-25', '345-678-901', 'bobjohnson@email.com', 'Canadian', '514-555-3456', '789 King Street', 'H3G 1A7'),
(4, 'Emily', 'Davis', 'Pharmacist', '1987-04-15', '456-789-012', 'emilydavis@email.com', 'Canadian', '514-555-4567', '246 Main Street', 'H3G 1A7'),
(5, 'William', 'Brown', 'Receptionist', '1990-05-30', '567-890-123', 'williambrown@email.com', 'Canadian', '514-555-5678', '369 Park Street', 'H3G 1A7'),
(6, 'Ashley', 'Taylor', 'Administrative personnel', '1992-06-15', '678-901-234', 'ashleytaylor@email.com', 'Canadian', '514-555-6789', '159 Eglinton Avenue', 'H3G 1A7'),
(7, 'Michael', 'Thomas', 'Security personnel', '1995-07-25', '789-012-345', 'michaelthomas@email.com', 'Canadian', '514-555-7890', '425 Bloor Street', 'H3G 1A7'),
(8, 'Sarah', 'Moore', 'Regular employee', '1997-08-15', '890-123-456', 'sarahmoore@email.com', 'Canadian', '514-555-8901', '520 Danforth Avenue', 'H3G 1A7'),
(9, 'David', 'Jackson', 'Nurse', '2000-09-30', '901-234-567', 'davidjackson@email.com', 'Canadian', '514-555-9012', '235 Kingsway', 'H3G 1A7'),
(10, 'Jessica', 'Miller', 'Doctor', '2002-10-15', '012-345-678', 'jessicamiller@email.com', 'Canadian', '514-555-0123', '867 King Street', 'H3G 1A7'),
(11, 'Richard', 'Davis', 'Cashier', '1980-11-01', '111-456-789', 'richarddavis@email.com', 'Canadian', '514-555-1112', '456 Park Avenue', 'H3G 1A7'),
(12, 'Elizabeth', 'Martin', 'Pharmacist', '1982-12-15', '222-567-890', 'elizabethmartin@email.com', 'Canadian', '514-555-2223', '789 Queen Street', 'H3G 1A7'),
(13, 'Christopher', 'Brown', 'Receptionist', '1985-01-25', '333-678-901', 'christopherbrown@email.com', 'Canadian', '514-555-3334', '246 Main Street', 'H3G 1A7'),
(14, 'Matthew', 'Taylor', 'Administrative personnel', '1987-02-15', '444-789-012', 'matthewtaylor@email.com', 'Canadian', '514-555-4445', '369 Park Street', 'H3G 1A7'),
(15, 'Daniel', 'Thomas', 'Security personnel', '1990-03-30', '555-890-123', 'danielthomas@email.com', 'Canadian', '514-555-5556', '159 Eglinton Avenue', 'H3G 1A7'),
(16, 'Sarah', 'Moore', 'Regular employee', '1992-04-15', '666-901-234', 'sarahmoore@email.com', 'Canadian', '514-555-6667', '425 Bloor Street', 'H3G 1A7'),
(17, 'John', 'Davis', 'Doctor', '1995-05-25', '777-012-345', 'johndavis@email.com', 'Canadian', '514-555-7778', '520 Danforth Avenue', 'H3G 1A7'),
(18, 'Jessica', 'Wilson', 'Nurse', '1997-06-15', '888-123-456', 'jessicawilson@email.com', 'Canadian', '514-555-8889', '235 Kingsway', 'H3G 1A7'),
(19, 'William', 'Anderson', 'Doctor', '2000-07-30', '999-234-567', 'williamanderson@email.com', 'Canadian', '514-555-9990', '867 King Street', 'H3G 1A7'),
(20, 'Emily', 'Thomas', 'Nurse', '2002-08-15', '000-345-678', 'emilythomas@email.com', 'Canadian', '514-555-0001', '123 Main Street', 'H3G 1A7'),
(21, 'John', 'Nguyen', 'Doctor', '1990-01-01', '001-786-278', 'johnnguyen@email.com', 'French', '514-555-2672', '78 Saint-Mary Street', 'J4X 9T2'),
(22, 'Mary', 'Tran', 'Receptionist', '1980-09-01', '189-298-872', 'marytran@email.com', 'Canadian', '514-555-2872', '728 Saint-Louis Street', 'J5X 4T6'),
(23, 'Eddy', 'Wang', 'Doctor', '1987-08-10', '020-871-188', 'eddywang@email.com', 'Canadian', '762-265-2982', '22 Saint-Louis Street', 'N5T 2IE'),
(24, 'Jenny', 'Wang', 'Nurse', '1992-07-11', '782-892-287', 'jennywang@email.com', 'Canadian', '728-892-6721', '192 Saint-Justin Street', 'J5T 8G4'),
(25, 'Henry', 'Aspen', 'Administrative personnel', '1965-01-01', '128-476-709', 'henryaspen@email.com', 'Canadian', '514-344-1234', '143 Good Street', 'H3S 1W8'),
(26, 'Judy', 'Chicago', 'Administrative personnel', '1952-02-10', '284-567-830', 'judychicago@email.com', 'Canadian', '514-756-2315', '46 Prince Street', 'H4V 1B8'),
(27, 'Bob', 'Dylan', 'Administrative personnel', '1978-08-25', '365-658-991', 'bobdylan@email.com', 'Canadian', '514-255-7456', '9 Best Street', 'H3J 1C7'),
(28, 'David', 'Kwan', 'Administrative personnel', '1964-04-15', '582-779-012', 'davidkwan@email.com', 'Canadian', '514-244-4577', '24 Worst Street', 'H6A 1B4'),
(29, 'Benjamin', 'Booth', 'Administrative personnel', '1980-05-24', '517-830-133', 'benjaminbooth@email.com', 'Canadian', '514-265-5238', '9 Kent Street', 'H8D 1F3'),
(30, 'Morgane', 'Dion', 'Administrative personnel', '1982-06-15', '558-911-234', 'morganedion@email.com', 'Canadian', '514-285-5709', '15 Ellendale Avenue', 'H3S 1W7'),
(31, 'Noemie', 'Duke', 'Administrative personnel', '1975-05-24', '709-002-045', 'noemieduke@email.com', 'Canadian', '514-736-2849', '45 Blood Street', 'H4T 1C3'),
(32, 'Sarah', 'Lance', 'Administrative personnel', '1976-09-19', '892-173-466', 'sarahlance@email.com', 'Canadian', '514-589-6435', '52 Lake Avenue', 'H6J 8P5'),
(33, 'Celia', 'Kremer', 'Administrative personnel', '1977-08-24', '911-244-577', 'celiakremer@email.com', 'Canadian', '514-887-625', '25 Bloom Avenue', 'H5G 3S5'),
(34, 'Dermot', 'Keller', 'Administrative personnel', '1949-11-10', '002-300-688', 'dermotkeller@email.com', 'Canadian', '514-838-6811', '77 Queens Street', 'H9D 5B5'),
(35, 'Jerome', 'Ferrer', 'Doctor', '1964-09-17', '452-390-675', 'jeromeferrer@email.com', 'Canadian', '514-344-5707', '3275 Champlain Street', 'G1B 0A9'),
(36, 'Maelle', 'Campagnie', 'Nurse', '1994-09-19', '476-839-905', 'maellecampagnie@email.com', 'Canadian', '514-366-7777', '32 Saint-Paul Street', 'H3G 1A7'),
(37, 'Clemence', 'Fouquet', 'Nurse', '1993-12-05', '389-756-832', 'clemencefouquet@email.com', 'Canadian', '514-855-3669', '5 Grace Street', 'H9D 5B5'),
(38, 'Nadira', 'Rafai', 'Nurse', '1984-08-07', '568-934-835', 'nadirarafai@email.com', 'Canadian', '514-865-3778', '27 Martin Street', 'H4T 1C3'),
(39, 'Anais', 'Perez', 'Nurse', '1992-01-27', '962-038-285', 'anaisperez@email.com', 'Canadian', '514-438-4927', '75 Marie Street', 'H4V 1B8'),
(40, 'David', 'Klein', 'Receptionist', '1952-04-23', '341-528-491', 'davidklein@email.com', 'Canadian', '514-684-5328', '11 Rockland Street', 'H3S 1W8');

INSERT INTO Managers (EmployeeID) VALUES
(6),
(14),
(25),
(26),
(27),
(28),
(29),
(30),
(31),
(32),
(33),
(34);

INSERT INTO Vaccines (VaccineID, EmployeeID, FacilityID, Type, DoseNumber, Date) VALUES
(1, 1, 1, 'Pfizer', 1, '2022-12-01'),
(2, 1, 1, 'Pfizer', 2, '2023-01-15'),
(3, 2, 2, 'Moderna', 1, '2022-11-01'),
(4, 2, 2, 'Moderna', 2, '2023-02-01'),
(5, 3, 3, 'AstraZeneca', 1, '2022-10-01'),
(6, 3, 3, 'AstraZeneca', 2, '2023-01-01'),
(7, 4, 4, 'Johnson & Johnson', 1, '2022-09-01'),
(8, 4, 4, 'Johnson & Johnson', 2, '2023-01-20'),
(9, 5, 5, 'Pfizer', 1, '2022-08-01'),
(10, 5, 5, 'Pfizer', 2, '2023-02-10'),
(11, 6, 6, 'Moderna', 1, '2022-07-01'),
(12, 6, 6, 'Moderna', 2, '2023-01-15'),
(13, 7, 7, 'AstraZeneca', 1, '2022-06-01'),
(14, 7, 7, 'AstraZeneca', 2, '2023-02-05'),
(15, 8, 8, 'Johnson & Johnson', 1, '2022-05-01'),
(16, 8, 8, 'Johnson & Johnson', 2, '2023-01-25'),
(17, 9, 9, 'Pfizer', 1, '2022-04-01'),
(18, 9, 9, 'Pfizer', 2, '2023-02-15'),
(19, 10, 10, 'Moderna', 1, '2022-03-01'),
(20, 10, 10, 'Moderna', 2, '2023-01-30'),
(21, 11, 1, 'Pfizer', 1, '2022-12-01'),
(22, 11, 1, 'Pfizer', 2, '2023-01-15'),
(23, 12, 2, 'Moderna', 1, '2022-11-01'),
(24, 12, 2, 'Moderna', 2, '2023-02-01'),
(25, 13, 3, 'AstraZeneca', 1, '2022-10-01'),
(26, 13, 3, 'AstraZeneca', 2, '2023-01-01'),
(27, 14, 4, 'Johnson & Johnson', 1, '2022-09-01'),
(28, 14, 4, 'Johnson & Johnson', 2, '2023-01-20'),
(29, 15, 5, 'Pfizer', 1, '2022-08-01'),
(30, 15, 5, 'Pfizer', 2, '2023-02-10'),
(31, 16, 6, 'Moderna', 1, '2022-07-01'),
(32, 16, 6, 'Moderna', 2, '2023-01-15'),
(33, 17, 7, 'AstraZeneca', 1, '2022-06-01'),
(34, 17, 7, 'AstraZeneca', 2, '2023-02-05'),
(35, 18, 8, 'Johnson & Johnson', 1, '2022-05-01'),
(36, 18, 8, 'Johnson & Johnson', 2, '2023-01-25'),
(37, 19, 9, 'Pfizer', 1, '2022-04-01'),
(38, 19, 9, 'Pfizer', 2, '2023-02-15'),
(39, 20, 10, 'Moderna', 1, '2022-03-01'),
(40, 20, 10, 'Moderna', 2, '2023-01-30'),
(41, 21, 1, 'Pfizer', 1, '2022-12-01'),
(42, 21, 1, 'Pfizer', 2, '2023-01-15'),
(43, 22, 2, 'Moderna', 1, '2022-11-01'),
(44, 22, 2, 'Moderna', 2, '2023-02-01'),
(45, 23, 3, 'AstraZeneca', 1, '2022-10-01'),
(46, 23, 3, 'AstraZeneca', 2, '2023-01-01'),
(47, 24, 4, 'Johnson & Johnson', 1, '2022-09-01'),
(48, 24, 4, 'Johnson & Johnson', 2, '2023-01-20'),
(49, 25, 5, 'Pfizer', 1, '2022-08-01'),
(50, 25, 5, 'Pfizer', 2, '2023-02-10'),
(51, 26, 6, 'Moderna', 1, '2022-07-01'),
(52, 26, 6, 'Moderna', 2, '2023-01-15'),
(53, 27, 7, 'AstraZeneca', 1, '2022-06-01'),
(54, 27, 7, 'AstraZeneca', 2, '2023-02-05'),
(55, 28, 8, 'Johnson & Johnson', 1, '2022-05-01'),
(56, 28, 8, 'Johnson & Johnson', 2, '2023-01-25'),
(57, 29, 9, 'Pfizer', 1, '2022-04-01'),
(58, 29, 9, 'Pfizer', 2, '2023-02-15'),
(59, 30, 10, 'Moderna', 1, '2022-03-01'),
(60, 30, 10, 'Moderna', 2, '2023-01-30'),
(61, 31, 1, 'Pfizer', 1, '2022-12-01'),
(62, 31, 1, 'Pfizer', 2, '2023-01-15'),
(63, 32, 2, 'Moderna', 1, '2022-11-01'),
(64, 32, 2, 'Moderna', 2, '2023-02-01'),
(65, 33, 3, 'AstraZeneca', 1, '2022-10-01'),
(66, 33, 3, 'AstraZeneca', 2, '2023-01-01'),
(67, 34, 4, 'Johnson & Johnson', 1, '2022-09-01'),
(68, 34, 4, 'Johnson & Johnson', 2, '2023-01-20'),
(69, 35, 5, 'Pfizer', 1, '2022-08-01'),
(70, 35, 5, 'Pfizer', 2, '2023-02-10'),
(71, 36, 2, 'Moderna', 1, '2022-11-01'),
(72, 36, 2, 'Moderna', 2, '2023-02-01'),
(73, 37, 3, 'AstraZeneca', 1, '2022-10-01'),
(74, 37, 3, 'AstraZeneca', 2, '2023-01-01'),
(75, 38, 4, 'Johnson & Johnson', 1, '2022-09-01'),
(76, 38, 4, 'Johnson & Johnson', 2, '2023-01-20'),
(77, 39, 5, 'Pfizer', 1, '2022-08-01'),
(78, 39, 5, 'Pfizer', 2, '2023-02-10');

INSERT INTO Infections(InfectionID, EmployeeID, Type, Date) VALUES
(1, 1, 'COVID-19', '2022-12-01'),
(2, 2, 'COVID-19', '2023-03-31'),
(3, 3, 'COVID-19', '2023-01-05'),
(4, 4, 'COVID-19', '2023-01-10'),
(5, 5, 'COVID-19', '2023-01-15'),
(6, 6, 'COVID-19', '2022-12-20'),
(7, 7, 'COVID-19', '2022-12-25'),
(8, 8, 'COVID-19', '2023-01-01'),
(9, 9, 'COVID-19', '2023-01-05'),
(10, 10, 'COVID-19', '2023-03-31'),
(11, 11, 'SARS-Cov-2 Variant', '2023-01-15'),
(12, 12, 'SARS-Cov-2 Variant', '2022-12-20'),
(13, 13, 'SARS-Cov-2 Variant', '2022-12-25'),
(14, 14, 'SARS-Cov-2 Variant', '2023-01-01'),
(15, 15, 'SARS-Cov-2 Variant', '2023-01-05'),
(16, 1, 'Flu', '2023-01-10'),
(17, 2, 'Flu', '2023-01-15'),
(18, 3, 'Flu', '2022-12-20'),
(19, 4, 'Flu', '2022-12-25'),
(20, 5, 'Flu', '2023-01-01'),
(21, 21, 'COVID-19', '2023-03-31'),
(22, 6, "COVID-19", "2022-09-05"),
(23, 6, "COVID-19", "2023-03-31"),
(24, 2, "Flu", "2022-08-05"),
(25, 2, "SARS-Cov-2 Variant", "2022-08-05"),
(26, 23, "COVID-19", "2022-12-01"),
(27, 19, "COVID-19", "2023-03-31"),
(28, 1, 'COVID-19', '2022-12-20'),
(29, 1, 'COVID-19', '2023-02-01'),
(30, 9, 'COVID-19', '2023-01-25'),
(31, 9, 'COVID-19', '2023-02-15'),
(32, 2, 'COVID-19', '2022-10-20'),
(33, 2, 'COVID-19', '2022-11-20'),
(34, 23, "COVID-19", "2023-01-03"),
(35, 23, "COVID-19", "2023-02-06"),
(36, 10, 'COVID-19', '2022-12-28'),
(37, 10, 'COVID-19', '2023-02-01'),
(38, 36, 'COVID-19', '2022-01-25'),
(39, 36, 'COVID-19', '2022-04-15'),
(40, 36, 'COVID-19', '2022-10-20'),
(41, 37, 'COVID-19', '2022-01-20'),
(42, 37, "COVID-19", "2022-05-03"),
(43, 37, "COVID-19", "2022-11-01"),
(44, 38, 'COVID-19', '2022-01-28'),
(45, 38, 'COVID-19', '2022-03-01'),
(46, 38, 'COVID-19', '2022-08-11'),
(47, 39, 'COVID-19', '2022-03-26'),
(48, 39, 'COVID-19', '2022-06-18'),
(49, 39, 'COVID-19', '2022-09-22');

INSERT INTO Employment (FacilityID, EmployeeID, ContractID, StartDate, EndDate) VALUES
(1, 1, 1, '2022-12-01', NULL),
(2, 2, 2, '2022-12-02', NULL),
(3, 3, 3, '2022-12-03', NULL),
(4, 4, 4, '2022-12-04', NULL),
(5, 5, 5, '2022-12-05', NULL),
(6, 6, 6, '2022-12-06', NULL),
(7, 7, 7, '2022-12-07', NULL),
(8, 8, 8, '2022-12-08', NULL),
(9, 9, 9, '2022-12-09', NULL),
(10, 10, 10, '2022-12-10', NULL),
(1, 11, 11, '2022-12-11', NULL),
(2, 12, 12, '2022-12-12', NULL),
(3, 13, 13, '2022-12-13', NULL),
(4, 14, 14, '2022-12-14', NULL),
(5, 15, 15, '2022-12-15', NULL),
(6, 16, 16, '2022-12-16', NULL),
(7, 17, 17, '2022-12-17', NULL),
(8, 18, 18, '2022-12-18', NULL),
(9, 19, 19, '2022-12-19', '2023-02-11'),
(10, 20, 20, '2022-12-20', '2023-02-12'),
(11, 1, 21, "2021-01-01", "2022-11-01"),
(12, 4, 22, "2020-12-15", "2022-11-15"),
(1, 21, 23, "2020-01-01", null),
(1, 22, 24, "2021-12-03", null),
(11, 23, 25, "2020-01-02", null),
(12, 24, 26, "2022-01-02", null),
(1, 14, 27, '2023-01-01', NULL),
(2, 25, 28, '2023-01-02', NULL),
(2, 1, 29, '2023-01-03', NULL),
(2, 3, 30, '2023-01-04', NULL),
(3, 26, 31, '2023-01-05', NULL),
(3, 1, 32, '2023-01-06', NULL),
(3, 2, 33, '2023-01-07', NULL),
(4, 27, 34, '2023-01-08', NULL),
(4, 1, 35, '2023-01-09', NULL),
(4, 2, 36, '2023-01-10', NULL),
(5, 28, 37, '2023-01-11', NULL),
(5, 2, 38, '2023-01-12', NULL),
(5, 3, 39, '2023-01-13', NULL),
(6, 3, 40, '2023-01-14', NULL),
(6, 4, 41, '2023-01-15', NULL),
(6, 5, 42, '2023-01-16', NULL),
(7, 29, 43, '2023-01-17', NULL),
(7, 4, 44, '2023-01-18', NULL),
(7, 5, 45, '2023-01-19', NULL),
(8, 30, 46, '2023-01-20', NULL),
(8, 4, 47, '2023-01-21', NULL),
(8, 5, 48, '2023-01-22', NULL),
(9, 31, 49, '2023-01-23', NULL),
(9, 6, 50, '2023-01-24', NULL),
(9, 7, 51, '2023-01-25', NULL),
(9, 8, 52, '2023-01-26', NULL),
(10, 32, 53, '2023-01-27', NULL),
(10, 6, 54, '2023-01-28', NULL),
(10, 7, 55, '2023-01-29', NULL),
(10, 8, 56, '2023-01-30', NULL),
(11, 33, 57, '2023-01-31', NULL),
(11, 6, 58, '2023-02-01', NULL),
(11, 7, 59, '2023-02-02', NULL),
(11, 8, 60, '2023-02-03', NULL),
(12, 34, 61, '2023-02-04', NULL),
(12, 9, 62, '2023-02-05', NULL),
(12, 10, 63, '2023-02-06', NULL),
(12, 11, 64, '2023-02-07', NULL),
(10, 19, 65, '2023-02-12', NULL),
(6, 35, 66, '2022-12-01', NULL),
(13, 20, 67, '2023-02-13', NULL),
(13, 36, 68, '2021-01-01', NULL),
(13, 37, 69, '2021-01-01', NULL),
(13, 38, 70, '2021-01-01', NULL),
(13, 39, 71, '2021-01-01', NULL),
(1, 40, 72, '2023-01-01', NULL);

INSERT INTO Managing (FacilityID, EmployeeID, StartDate, EndDate) VALUES
(1, 14, '2022-12-01', NULL),
(2, 25, '2022-12-02', NULL),
(3, 26, '2022-12-03', NULL),
(4, 27, '2022-12-04', NULL),
(5, 28, '2022-12-05', NULL),
(6, 6, '2022-12-06', NULL),
(7, 29, '2022-12-07', NULL),
(8, 30, '2022-12-08', NULL),
(9, 31, '2022-12-09', NULL),
(10, 32, '2022-12-10', NULL),
(11, 33, '2022-12-11', NULL),
(12, 34, '2022-12-12', NULL);

INSERT INTO Schedule (FacilityID, EmployeeID, Date, StartTime, EndTime) VALUES
(1, 1, '2023-03-06', '08:00', '12:00'),
(2, 1, '2023-03-07', '08:00', '12:00'),
(3, 1, '2023-03-08', '08:00', '12:00'),
(4, 1, '2023-03-09', '08:00', '12:00'),
(1, 1, '2023-03-13', '08:00', '12:00'),
(2, 1, '2023-03-14', '08:00', '12:00'),
(3, 1, '2023-03-15', '08:00', '12:00'),
(4, 1, '2023-03-16', '08:00', '12:00'),
(1, 1, '2023-03-20', '08:00', '12:00'),
(2, 1, '2023-03-21', '08:00', '12:00'),
(3, 1, '2023-03-22', '08:00', '12:00'),
(4, 1, '2023-03-23', '08:00', '12:00'),
(2, 2, '2023-03-06', '12:00', '16:00'),
(3, 2, '2023-03-07', '12:00', '16:00'),
(4, 2, '2023-03-08', '12:00', '16:00'),
(5, 2, '2023-03-09', '12:00', '16:00'),
(2, 2, '2023-03-13', '12:00', '16:00'),
(3, 2, '2023-03-14', '12:00', '16:00'),
(4, 2, '2023-03-15', '12:00', '16:00'),
(5, 2, '2023-03-16', '12:00', '16:00'),
(2, 2, '2023-03-20', '12:00', '16:00'),
(3, 2, '2023-03-21', '12:00', '16:00'),
(4, 2, '2023-03-22', '12:00', '16:00'),
(5, 2, '2023-03-23', '12:00', '16:00'),
(2, 3, '2023-03-06', '16:00', '20:00'),
(3, 3, '2023-03-07', '16:00', '20:00'),
(5, 3, '2023-03-08', '16:00', '20:00'),
(6, 3, '2023-03-09', '16:00', '20:00'),
(2, 3, '2023-03-13', '16:00', '20:00'),
(3, 3, '2023-03-14', '16:00', '20:00'),
(5, 3, '2023-03-15', '16:00', '20:00'),
(6, 3, '2023-03-16', '16:00', '20:00'),
(2, 3, '2023-03-20', '16:00', '20:00'),
(3, 3, '2023-03-21', '16:00', '20:00'),
(5, 3, '2023-03-22', '16:00', '20:00'),
(6, 3, '2023-03-23', '16:00', '20:00'),
(4, 4, '2023-03-06', '20:00', '23:59'),
(6, 4, '2023-03-07', '20:00', '23:59'),
(7, 4, '2023-03-08', '20:00', '23:59'),
(8, 4, '2023-03-09', '20:00', '23:59'),
(4, 4, '2023-03-13', '20:00', '23:59'),
(6, 4, '2023-03-14', '20:00', '23:59'),
(7, 4, '2023-03-15', '20:00', '23:59'),
(8, 4, '2023-03-16', '20:00', '23:59'),
(4, 4, '2023-03-20', '20:00', '23:59'),
(6, 4, '2023-03-21', '20:00', '23:59'),
(7, 4, '2023-03-22', '20:00', '23:59'),
(8, 4, '2023-03-23', '20:00', '23:59'),
(5, 5, '2023-03-06', '08:00', '12:00'),
(6, 5, '2023-03-07', '08:00', '12:00'),
(7, 5, '2023-03-08', '08:00', '12:00'),
(8, 5, '2023-03-09', '08:00', '12:00'),
(5, 5, '2023-03-13', '08:00', '12:00'),
(6, 5, '2023-03-14', '08:00', '12:00'),
(7, 5, '2023-03-15', '08:00', '12:00'),
(8, 5, '2023-03-16', '08:00', '12:00'),
(5, 5, '2023-03-20', '08:00', '12:00'),
(6, 5, '2023-03-21', '08:00', '12:00'),
(7, 5, '2023-03-22', '08:00', '12:00'),
(8, 5, '2023-03-23', '08:00', '12:00'),
(6, 6, '2023-03-06', '12:00', '16:00'),
(9, 6, '2023-03-07', '12:00', '16:00'),
(10, 6, '2023-03-08', '12:00', '16:00'),
(11, 6, '2023-03-09', '12:00', '16:00'),
(6, 6, '2023-03-13', '12:00', '16:00'),
(9, 6, '2023-03-14', '12:00', '16:00'),
(10, 6, '2023-03-15', '12:00', '16:00'),
(11, 6, '2023-03-16', '12:00', '16:00'),
(6, 6, '2023-03-20', '12:00', '16:00'),
(9, 6, '2023-03-21', '12:00', '16:00'),
(10, 6, '2023-03-22', '12:00', '16:00'),
(11, 6, '2023-03-23', '12:00', '16:00'),
(7, 7, '2023-03-06', '16:00', '20:00'),
(9, 7, '2023-03-07', '16:00', '20:00'),
(10, 7, '2023-03-08', '16:00', '20:00'),
(11, 7, '2023-03-09', '16:00', '20:00'),
(7, 7, '2023-03-13', '16:00', '20:00'),
(9, 7, '2023-03-14', '16:00', '20:00'),
(10, 7, '2023-03-15', '16:00', '20:00'),
(11, 7, '2023-03-16', '16:00', '20:00'),
(7, 7, '2023-03-20', '16:00', '20:00'),
(9, 7, '2023-03-21', '16:00', '20:00'),
(10, 7, '2023-03-22', '16:00', '20:00'),
(11, 7, '2023-03-23', '16:00', '20:00'),
(8, 8, '2023-03-06', '20:00', '23:59'),
(9, 8, '2023-03-07', '20:00', '23:59'),
(10, 8, '2023-03-08', '20:00', '23:59'),
(11, 8, '2023-03-09', '20:00', '23:59'),
(8, 8, '2023-03-13', '20:00', '23:59'),
(9, 8, '2023-03-14', '20:00', '23:59'),
(10, 8, '2023-03-15', '20:00', '23:59'),
(11, 8, '2023-03-16', '20:00', '23:59'),
(8, 8, '2023-03-20', '20:00', '23:59'),
(9, 8, '2023-03-21', '20:00', '23:59'),
(10, 8, '2023-03-22', '20:00', '23:59'),
(11, 8, '2023-03-23', '20:00', '23:59'),
(9, 9, '2023-03-06', '20:00', '23:59'),
(9, 9, '2023-03-07', '20:00', '23:59'),
(12, 9, '2023-03-08', '20:00', '23:59'),
(12, 9, '2023-03-09', '20:00', '23:59'),
(9, 9, '2023-03-13', '20:00', '23:59'),
(9, 9, '2023-03-14', '20:00', '23:59'),
(12, 9, '2023-03-15', '20:00', '23:59'),
(12, 9, '2023-03-16', '20:00', '23:59'),
(9, 9, '2023-03-20', '20:00', '23:59'),
(9, 9, '2023-03-21', '20:00', '23:59'),
(12, 9, '2023-03-22', '20:00', '23:59'),
(12, 9, '2023-03-23', '20:00', '23:59'),
(10, 10, '2023-03-06', '12:00', '23:59'),
(10, 10, '2023-03-07', '12:00', '23:59'),
(12, 10, '2023-03-08', '12:00', '23:59'),
(12, 10, '2023-03-09', '12:00', '23:59'),
(10, 10, '2023-03-13', '12:00', '23:59'),
(10, 10, '2023-03-14', '12:00', '23:59'),
(12, 10, '2023-03-15', '12:00', '23:59'),
(12, 10, '2023-03-16', '12:00', '23:59'),
(10, 10, '2023-03-20', '12:00', '23:59'),
(10, 10, '2023-03-21', '12:00', '23:59'),
(12, 10, '2023-03-22', '12:00', '23:59'),
(12, 10, '2023-03-23', '12:00', '23:59'),
(10, 10, '2023-03-27', '12:00', '23:59'),
(10, 10, '2023-03-28', '12:00', '23:59'),
(12, 10, '2023-03-29', '12:00', '23:59'),
(12, 10, '2023-03-30', '12:00', '23:59'),
(1, 11, '2023-03-06', '8:00', '12:00'),
(12, 11, '2023-03-07', '8:00', '12:00'),
(1, 11, '2023-03-13', '8:00', '12:00'),
(12, 11, '2023-03-14', '8:00', '12:00'),
(1, 11, '2023-03-20', '8:00', '12:00'),
(12, 11, '2023-03-21', '8:00', '12:00'),
(2, 12, '2023-03-04', '8:00', '16:00'),
(2, 12, '2023-03-05', '8:00', '16:00'),
(2, 12, '2023-03-11', '8:00', '16:00'),
(2, 12, '2023-03-12', '8:00', '16:00'),
(2, 12, '2023-03-18', '8:00', '16:00'),
(2, 12, '2023-03-19', '8:00', '16:00'),
(3, 13, '2023-03-07', '8:00', '18:00'),
(3, 13, '2023-03-14', '8:00', '18:00'),
(3, 13, '2023-03-21', '8:00', '18:00'),
(1, 14, '2023-03-12', '8:00', '20:00'),
(1, 14, '2023-03-18', '8:00', '20:00'),
(4, 14, '2023-03-19', '14:00', '16:00'),
(5, 15, '2023-03-07', '8:00', '18:00'),
(5, 15, '2023-03-14', '8:00', '18:00'),
(5, 15, '2023-03-21', '8:00', '18:00'),
(6, 16, '2023-03-12', '8:00', '20:00'),
(6, 16, '2023-03-18', '8:00', '20:00'),
(6, 16, '2023-03-19', '14:00', '16:00'),
(7, 17, '2023-03-07', '8:00', '18:00'),
(7, 17, '2023-03-14', '8:00', '18:00'),
(7, 17, '2023-03-21', '8:00', '18:00'),
(8, 18, '2023-03-07', '8:00', '18:00'),
(8, 18, '2023-03-14', '8:00', '18:00'),
(8, 18, '2023-03-21', '8:00', '18:00'),
(10, 19, '2023-03-07', '8:00', '18:00'),
(10, 19, '2023-03-14', '8:00', '18:00'),
(10, 19, '2023-03-21', '8:00', '18:00'),
(10, 19, '2023-03-28', '8:00', '18:00'),
(1, 21, '2023-03-07', '8:00', '18:00'),
(1, 21, '2023-03-14', '8:00', '18:00'),
(1, 21, '2023-03-21', '8:00', '18:00'),
(1, 21, '2023-03-28', '8:00', '18:00'),
(1, 22, '2023-03-12', '8:00', '20:00'),
(1, 22, '2023-03-18', '8:00', '20:00'),
(1, 22, '2023-03-19', '14:00', '16:00'),
(11, 23, '2023-03-07', '8:00', '18:00'),
(11, 23, '2023-03-14', '8:00', '18:00'),
(11, 23, '2023-03-21', '8:00', '18:00'),
(11, 23, '2023-03-28', '8:00', '18:00'),
(12, 24, '2023-03-07', '8:00', '18:00'),
(12, 24, '2023-03-14', '8:00', '18:00'),
(12, 24, '2023-03-21', '8:00', '18:00'),
(12, 24, '2023-03-28', '8:00', '18:00'),
(2, 25, '2023-03-08', '16:00', '18:00'),
(2, 25, '2023-03-15', '16:00', '18:00'),
(2, 25, '2023-03-22', '16:00', '18:00'),
(2, 25, '2023-03-29', '16:00', '18:00'),
(3, 26, '2023-03-08', '16:00', '18:00'),
(3, 26, '2023-03-15', '16:00', '18:00'),
(3, 26, '2023-03-22', '16:00', '18:00'),
(3, 26, '2023-03-29', '16:00', '18:00'),
(4, 27, '2023-03-08', '16:00', '18:00'),
(4, 27, '2023-03-15', '16:00', '18:00'),
(4, 27, '2023-03-22', '16:00', '18:00'),
(4, 27, '2023-03-29', '16:00', '18:00'),
(5, 28, '2023-03-08', '16:00', '18:00'),
(5, 28, '2023-03-15', '16:00', '18:00'),
(5, 28, '2023-03-22', '16:00', '18:00'),
(5, 28, '2023-03-29', '16:00', '18:00'),
(7, 29, '2023-03-08', '16:00', '18:00'),
(7, 29, '2023-03-15', '16:00', '18:00'),
(7, 29, '2023-03-22', '16:00', '18:00'),
(7, 29, '2023-03-29', '16:00', '18:00'),
(8, 30, '2023-03-08', '16:00', '18:00'),
(8, 30, '2023-03-15', '16:00', '18:00'),
(8, 30, '2023-03-22', '16:00', '18:00'),
(8, 30, '2023-03-29', '16:00', '18:00'),
(9, 31, '2023-03-08', '16:00', '18:00'),
(9, 31, '2023-03-15', '16:00', '18:00'),
(9, 31, '2023-03-22', '16:00', '18:00'),
(9, 31, '2023-03-29', '16:00', '18:00'),
(10, 32, '2023-03-08', '16:00', '18:00'),
(10, 32, '2023-03-15', '16:00', '18:00'),
(10, 32, '2023-03-22', '16:00', '18:00'),
(10, 32, '2023-03-29', '16:00', '18:00'),
(11, 33, '2023-03-08', '16:00', '18:00'),
(11, 33, '2023-03-15', '16:00', '18:00'),
(11, 33, '2023-03-22', '16:00', '18:00'),
(11, 33, '2023-03-29', '16:00', '18:00'),
(12, 34, '2023-03-08', '16:00', '18:00'),
(12, 34, '2023-03-15', '16:00', '18:00'),
(12, 34, '2023-03-22', '16:00', '18:00'),
(12, 34, '2023-03-29', '16:00', '18:00'),
(6, 35, '2023-03-06', '00:00', '8:00'),
(6, 35, '2023-03-13', '00:00', '8:00'),
(6, 35, '2023-03-20', '00:00', '8:00'),
(6, 35, '2023-03-27', '00:00', '8:00'),
(13, 36, '2023-03-20', '08:00', '12:00'),
(13, 36, '2023-03-21', '08:00', '12:00'),
(13, 36, '2023-03-22', '08:00', '12:00'),
(13, 36, '2023-03-23', '08:00', '12:00'),
(13, 36, '2023-03-27', '08:00', '12:00'),
(13, 36, '2023-03-28', '08:00', '12:00'),
(13, 36, '2023-03-29', '08:00', '12:00'),
(13, 36, '2023-03-30', '08:00', '12:00'),
(13, 36, '2023-03-03', '08:00', '12:00'),
(13, 36, '2023-03-04', '08:00', '12:00'),
(13, 36, '2023-03-05', '08:00', '12:00'),
(13, 36, '2023-03-06', '08:00', '12:00'),
(13, 37, '2023-03-20', '08:00', '12:00'),
(13, 37, '2023-03-21', '08:00', '12:00'),
(13, 37, '2023-03-22', '08:00', '12:00'),
(13, 37, '2023-03-23', '08:00', '12:00'),
(13, 37, '2023-03-27', '08:00', '12:00'),
(13, 37, '2023-03-28', '08:00', '12:00'),
(13, 37, '2023-03-29', '08:00', '12:00'),
(13, 37, '2023-03-30', '08:00', '12:00'),
(13, 37, '2023-03-03', '08:00', '12:00'),
(13, 37, '2023-03-04', '08:00', '12:00'),
(13, 37, '2023-03-05', '08:00', '12:00'),
(13, 37, '2023-03-06', '08:00', '12:00'),
(13, 38, '2023-03-20', '08:00', '12:00'),
(13, 38, '2023-03-21', '08:00', '12:00'),
(13, 38, '2023-03-22', '08:00', '12:00'),
(13, 38, '2023-03-23', '08:00', '12:00'),
(13, 38, '2023-03-27', '08:00', '12:00'),
(13, 38, '2023-03-28', '08:00', '12:00'),
(13, 38, '2023-03-29', '08:00', '12:00'),
(13, 38, '2023-03-30', '08:00', '12:00'),
(13, 38, '2023-03-03', '08:00', '12:00'),
(13, 38, '2023-03-04', '08:00', '12:00'),
(13, 38, '2023-03-05', '08:00', '12:00'),
(13, 38, '2023-03-06', '08:00', '12:00'),
(13, 39, '2023-03-20', '08:00', '12:00'),
(13, 39, '2023-03-21', '08:00', '12:00'),
(13, 39, '2023-03-22', '08:00', '12:00'),
(13, 39, '2023-03-23', '08:00', '12:00'),
(13, 39, '2023-03-27', '08:00', '12:00'),
(13, 39, '2023-03-28', '08:00', '12:00'),
(13, 39, '2023-03-29', '08:00', '12:00'),
(13, 39, '2023-03-30', '08:00', '12:00'),
(13, 39, '2023-03-03', '08:00', '12:00'),
(13, 39, '2023-03-04', '08:00', '12:00'),
(13, 39, '2023-03-05', '08:00', '12:00'),
(13, 39, '2023-03-06', '08:00', '12:00'),
(13, 20, '2023-03-20', '08:00', '12:00'),
(13, 20, '2023-03-21', '08:00', '12:00'),
(13, 20, '2023-03-22', '08:00', '12:00'),
(13, 20, '2023-03-23', '08:00', '12:00'),
(13, 20, '2023-03-27', '08:00', '12:00'),
(13, 20, '2023-03-28', '08:00', '12:00'),
(13, 20, '2023-03-29', '08:00', '12:00'),
(13, 20, '2023-03-30', '08:00', '12:00'),
(13, 20, '2023-03-03', '08:00', '12:00'),
(13, 20, '2023-03-04', '08:00', '12:00'),
(13, 20, '2023-03-05', '08:00', '12:00'),
(13, 20, '2023-03-06', '08:00', '12:00'),
(13, 36, '2023-04-20', '08:00', '12:00'),
(13, 36, '2023-04-21', '08:00', '12:00'),
(13, 36, '2023-04-22', '08:00', '12:00'),
(13, 36, '2023-04-23', '08:00', '12:00'),
(13, 37, '2023-04-20', '08:00', '12:00'),
(13, 37, '2023-04-21', '08:00', '12:00'),
(13, 37, '2023-04-22', '08:00', '12:00'),
(13, 37, '2023-04-23', '08:00', '12:00'),
(13, 38, '2023-04-20', '08:00', '12:00'),
(13, 38, '2023-04-21', '08:00', '12:00'),
(13, 38, '2023-04-22', '08:00', '12:00'),
(13, 38, '2023-04-23', '08:00', '12:00'),
(13, 39, '2023-04-20', '08:00', '12:00'),
(13, 39, '2023-04-21', '08:00', '12:00'),
(13, 39, '2023-04-22', '08:00', '12:00'),
(13, 39, '2023-04-23', '08:00', '12:00'),
(13, 20, '2023-04-20', '08:00', '12:00'),
(13, 20, '2023-04-21', '08:00', '12:00'),
(13, 20, '2023-04-22', '08:00', '12:00'),
(13, 20, '2023-04-23', '08:00', '12:00');

INSERT INTO EmailLog (FacilityID, EmployeeID, Date, Subject, Body) VALUES
(2, 12, '2023-03-31', 'Warning', 'One of your colleagues
that you have worked with in the past two weeks have been infected with COVID-19'),
(3, 13, '2023-03-31', 'Warning', 'One of your colleagues
that you have worked with in the past two weeks have been infected with COVID-19'),
(4, 14, '2023-03-31', 'Warning', 'One of your colleagues
that you have worked with in the past two weeks have been infected with COVID-19'),
(5, 15, '2023-03-31', 'Warning', 'One of your colleagues
that you have worked with in the past two weeks have been infected with COVID-19'),
(10, 19, '2023-03-31', 'Warning', 'One of your colleagues
that you have worked with in the past two weeks have been infected with COVID-19'),
(12, 9, '2023-03-31', 'Warning', 'One of your colleagues
that you have worked with in the past two weeks have been infected with COVID-19'),
(12, 34, '2023-03-31', 'Warning', 'One of your colleagues
that you have worked with in the past two weeks have been infected with COVID-19'),
(10, 10, '2023-03-31', 'Warning', 'One of your colleagues
that you have worked with in the past two weeks have been infected with COVID-19');

DELIMITER $$
CREATE TRIGGER ScheduleInfectedNurseDoctor
BEFORE INSERT ON Schedule
FOR EACH ROW
BEGIN
  DECLARE EmpType VARCHAR(255);
  DECLARE InfectedDate DATE;
  SET EmpType = (SELECT e.Role FROM Employees e WHERE EmployeeID = NEW.EmployeeID);
  SET InfectedDate = (SELECT MAX(i.Date) FROM Infections i WHERE EmployeeID = NEW.EmployeeID AND i.Type = 'COVID-19');
  IF EmpType IN ('Nurse', 'Doctor') AND InfectedDate IS NOT NULL AND DATE_ADD(InfectedDate, INTERVAL 14 DAY) > NEW.Date THEN
      SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Cannot schedule an infected nurse or doctor within two weeks of infection.';
  END IF;
END;$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER CheckEmployeeVaccinationCovid 
BEFORE INSERT ON Schedule
FOR EACH ROW
BEGIN
  DECLARE VaccinationDate DATE;
  SET VaccinationDate = (
    SELECT MAX(Date) FROM Vaccines
    WHERE EmployeeID = NEW.EmployeeID AND
	    (Type = 'Pfizer' OR Type = 'Moderna' OR 'AstraZeneca' OR 'Johnson & Johnson') AND
	    Date BETWEEN DATE_SUB(NEW.Date, INTERVAL 6 MONTH) AND NEW.Date
  );
  IF VaccinationDate IS NULL THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Employee is not vaccinated against COVID-19 in the past six months. Employee can not be scheduled.';
  END IF;
END;$$
DELIMITER ;
