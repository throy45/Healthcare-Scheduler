INSERT INTO Facilities (FacilityID, Name, Type, Capacity, WebAddress, PhoneNumber, Address)
VALUES
(1, 'Hospital Maisonneuve Rosemont', 'Hospital', 500, 'www.centralhospital.com', '514-555-1234', '123 Main Street'),
(2, 'North CLSC', 'CLSC', 200, 'www.northCLSC.com', '514-555-2345', '456 Queen Street'),
(3, 'West Pharmacy', 'Pharmacy', 300, 'www.westpharmacy.com', '514-555-3456', '789 King Street'),
(4, 'East Hospital', 'Hospital', 400, 'www.easthospital.com', '514-555-4567', '246 College Street'),
(5, 'South Special instalment', 'Special instalment', 250, 'www.south.com', '514-555-5678', '369 Bathurst Street'),
(6, 'Midtown Hospital', 'Hospital', 450, 'www.midtownhospital.com', '514-555-6789', '159 Spadina Avenue'),
(7, 'Downtown CLSC', 'CLSC', 175, 'www.downtownCLSC.com', '514-555-7890', '425 Yonge Street'),
(8, 'Uptown Pharmacy', 'Pharmacy', 325, 'www.uptownpharmacy.com', '514-555-8901', '520 Bloor Street'),
(9, 'Harbourfront Hospital', 'Hospital', 375, 'www.harbourfronthospital.com', '514-555-9012', '235 Queens Quay West'),
(10, 'West End Special instalment', 'Special instalment', 225, 'www.westend.com', '514-555-0123', '867 Queen Street West'),
(11, 'General Hospital', 'Hospital', 200, 'www.generalhospital.com', '520-333-1333', '203 Saint-Peter Street'),
(12, 'Queen West CLSC', 'CLSC', 100, 'www.queenwestclsc.com', '520-333-9897', '1000 Saint-Louis Street');

INSERT INTO Employees (EmployeeID, FName, LName, Role, DoBirth, MedicareNumber, Email, Citizenship, PhoneNumber, Address)
VALUES
(1, 'John', 'Doe', 'Nurse', '1980-01-01', '123-456-789', 'johndoe@email.com', 'Canadian', '514-555-1234', '123 Main Street'),
(2, 'Jane', 'Smith', 'Doctor', '1982-02-15', '234-567-890', 'janesmith@email.com', 'Canadian', '514-555-2345', '456 Queen Street'),
(3, 'Bob', 'Johnson', 'Cashier', '1985-03-25', '345-678-901', 'bobjohnson@email.com', 'Canadian', '514-555-3456', '789 King Street'),
(4, 'Emily', 'Davis', 'Pharmacist', '1987-04-15', '456-789-012', 'emilydavis@email.com', 'Canadian', '514-555-4567', '246 Main Street'),
(5, 'William', 'Brown', 'Receptionist', '1990-05-30', '567-890-123', 'williambrown@email.com', 'Canadian', '514-555-5678', '369 Park Street'),
(6, 'Ashley', 'Taylor', 'Administrative personnel', '1992-06-15', '678-901-234', 'ashleytaylor@email.com', 'Canadian', '514-555-6789', '159 Eglinton Avenue'),
(7, 'Michael', 'Thomas', 'Security personnel', '1995-07-25', '789-012-345', 'michaelthomas@email.com', 'Canadian', '514-555-7890', '425 Bloor Street'),
(8, 'Sarah', 'Moore', 'Regular employee', '1997-08-15', '890-123-456', 'sarahmoore@email.com', 'Canadian', '514-555-8901', '520 Danforth Avenue'),
(9, 'David', 'Jackson', 'Nurse', '2000-09-30', '901-234-567', 'davidjackson@email.com', 'Canadian', '514-555-9012', '235 Kingsway'),
(10, 'Jessica', 'Miller', 'Doctor', '2002-10-15', '012-345-678', 'jessicamiller@email.com', 'Canadian', '514-555-0123', '867 King Street'),
(11, 'Richard', 'Davis', 'Cashier', '1980-11-01', '111-456-789', 'richarddavis@email.com', 'Canadian', '514-555-1112', '456 Park Avenue'),
(12, 'Elizabeth', 'Martin', 'Pharmacist', '1982-12-15', '222-567-890', 'elizabethmartin@email.com', 'Canadian', '514-555-2223', '789 Queen Street'),
(13, 'Christopher', 'Brown', 'Receptionist', '1985-01-25', '333-678-901', 'christopherbrown@email.com', 'Canadian', '514-555-3334', '246 Main Street'),
(14, 'Matthew', 'Taylor', 'Administrative personnel', '1987-02-15', '444-789-012', 'matthewtaylor@email.com', 'Canadian', '514-555-4445', '369 Park Street'),
(15, 'Daniel', 'Thomas', 'Security personnel', '1990-03-30', '555-890-123', 'danielthomas@email.com', 'Canadian', '514-555-5556', '159 Eglinton Avenue'),
(16, 'Sarah', 'Moore', 'Regular employee', '1992-04-15', '666-901-234', 'sarahmoore@email.com', 'Canadian', '514-555-6667', '425 Bloor Street'),
(17, 'John', 'Davis', 'Doctor', '1995-05-25', '777-012-345', 'johndavis@email.com', 'Canadian', '514-555-7778', '520 Danforth Avenue'),
(18, 'Jessica', 'Wilson', 'Nurse', '1997-06-15', '888-123-456', 'jessicawilson@email.com', 'Canadian', '514-555-8889', '235 Kingsway'),
(19, 'William', 'Anderson', 'Doctor', '2000-07-30', '999-234-567', 'williamanderson@email.com', 'Canadian', '514-555-9990', '867 King Street'),
(20, 'Emily', 'Thomas', 'Nurse', '2002-08-15', '000-345-678', 'emilythomas@email.com', 'Canadian', '514-555-0001', '123 Main Street'),
(21, 'John', 'Nguyen', 'Doctor', '1990-01-01', '001-786-278', 'johnnguyen@email.com', 'French', '514-555-2672', '78 Saint-Mary Street'),
(22, 'Mary', 'Tran', 'Receptionist', '1980-09-01', '189-298-872', 'marytran@email.com', 'Canadian', '514-555-2872', '728 Saint-Louis Street'),
(23, 'Eddy', 'Wang', 'Doctor', '1987-08-10', '020-871-188', 'eddywang@email.com', 'Canadian', '762-265-2982', '22 Saint-Louis Street'),
(24, 'Jenny', 'Wang', 'Nurse', '1992-07-11', '782-892-287', 'jennywang@email.com', 'Canadian', '728-892-6721', '192 Saint-Justin Street');

INSERT INTO Manager (EmployeeID) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10);

INSERT INTO Vaccines (VaccineID, EmployeeID, FacilityID, Type, DoseNumber, Date)
VALUES
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
(20, 10, 10, 'Moderna', 2, '2023-01-30');

INSERT INTO Infections(InfectionID, EmployeeID, Type, Date)
VALUES
(1, 1, 'COVID-19', '2022-12-01'),
(2, 2, 'COVID-19', '2023-01-01'),
(3, 3, 'COVID-19', '2023-01-05'),
(4, 4, 'COVID-19', '2023-01-10'),
(5, 5, 'COVID-19', '2023-01-15'),
(6, 6, 'COVID-19', '2022-12-20'),
(7, 7, 'COVID-19', '2022-12-25'),
(8, 8, 'COVID-19', '2023-01-01'),
(9, 9, 'COVID-19', '2023-01-05'),
(10, 10, 'COVID-19', '2023-01-10'),
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
(21, 21, 'COVID-19', '2023-01-02'),
(22, 6, "COVID-19", "2022-09-05"),
(23, 6, "COVID-19", "2022-06-05"),
(24, 2, "Flu", "2022-08-05"),
(25, 2, "SARS-Cov-2 Variant", "2022-08-05"),
(26, 23, "COVID-19", "2022-12-01");

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
(12, 24, 26, "2022-01-02", null);

INSERT INTO Managers (FacilityID, EmployeeID, StartDate, EndDate) VALUES
(1, 1, '2022-12-01', NULL),
(2, 2, '2022-12-02', NULL),
(3, 3, '2022-12-03', NULL),
(4, 4, '2022-12-04', NULL),
(5, 5, '2022-12-05', NULL),
(6, 6, '2022-12-06', NULL),
(7, 7, '2022-12-07', NULL),
(8, 8, '2022-12-08', NULL),
(9, 9, '2022-12-09', NULL),
(10, 10, '2022-12-10', NULL);

INSERT INTO FacilityPC (FacilityID, PostalCode)
VALUES
(1, 'H3G 1B3'),
(2, 'H3G 1Z7'),
(3, 'H3G 1A1'),
(4, 'H3G 1R5'),
(5, 'H3G 2S6'),
(6, 'H3G 2C2'),
(7, 'H3G 1J5'),
(8, 'H3G 1Y2'),
(9, 'H3G 2G8'),
(10, 'H3G 1G3'),
(11, 'X3Y 6S8'),
(12, 'L2C 4T9');

INSERT INTO EmployeePC (EmployeeID, PostalCode)
VALUES
(1, 'H3G 1A7'),
(2, 'H3G 1A7'),
(3, 'H3G 1A7'),
(4, 'H3G 1A7'),
(5, 'H3G 1A7'),
(6, 'H3G 1A7'),
(7, 'H3G 1A7'),
(8, 'H3G 1A7'),
(9, 'H3G 1A7'),
(10, 'H3G 1A7'),
(11, 'H3G 1A7'),
(12, 'H3G 1A7'),
(13, 'H3G 1A7'),
(14, 'H3G 1A7'),
(15, 'H3G 1A7'),
(16, 'H3G 1A7'),
(17, 'H3G 1A7'),
(18, 'H3G 1A7'),
(19, 'H3G 1A7'),
(20, 'H3G 1A7'),
(21, 'J4X 9T2'),
(22, 'J5X 4T6'),
(23, 'N5T 2IE'),
(24, 'J5T 8G4');
