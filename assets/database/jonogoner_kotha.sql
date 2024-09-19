-- drop database if exists jonogoner_kotha;
-- create database jonogoner_kotha;
-- use jonogoner_kotha;


-- Disable foreign key checks before dropping tables
SET FOREIGN_KEY_CHECKS = 0;

-- 1. User Table
CREATE TABLE User (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Role ENUM('Admin', 'User') DEFAULT 'User',
    ProfilePicture VARCHAR(255),
    DateRegistered DATETIME DEFAULT CURRENT_TIMESTAMP,
    LastLogin DATETIME,
    LanguagePreference VARCHAR(20)
) ENGINE=InnoDB;



-- 2. Category Table
CREATE TABLE Category (
    CategoryID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL UNIQUE,
    Description TEXT
) ENGINE=InnoDB;

-- 3. GovernmentProject Table
CREATE TABLE GovernmentProject (
    ProjectID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255) NOT NULL,
    Description TEXT,
    Budget DECIMAL(15,2),
    StartDate DATE,
    EndDate DATE,
    Progress INT,
    Status ENUM('On Track', 'Delayed', 'Completed') DEFAULT 'On Track',
    CHECK (Progress BETWEEN 0 AND 100)
) ENGINE=InnoDB;

-- 4. Issue Table
CREATE TABLE Issue (
    IssueID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255) NOT NULL,
    Description TEXT NOT NULL,
    CategoryID INT,
    Location VARCHAR(255),
    DateSubmitted DATETIME DEFAULT CURRENT_TIMESTAMP,
    Status ENUM('Open', 'In Progress', 'Resolved') DEFAULT 'Open',
    SubmittedBy INT,
    FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID),
    FOREIGN KEY (SubmittedBy) REFERENCES User(UserID) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 5. WhistleblowerReport Table
CREATE TABLE WhistleblowerReport (
    ReportID INT AUTO_INCREMENT PRIMARY KEY,
    Description TEXT NOT NULL,
    CategoryID INT,
    DateSubmitted DATETIME DEFAULT CURRENT_TIMESTAMP,
    Attachments VARCHAR(255),
    TrackingCode VARCHAR(100) UNIQUE NOT NULL,
    Status ENUM('New', 'Under Review', 'Resolved') DEFAULT 'New',
    FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID)
    -- No UserID to maintain anonymity
) ENGINE=InnoDB;

-- 6. Solution Table
CREATE TABLE Solution (
    SolutionID INT AUTO_INCREMENT PRIMARY KEY,
    IssueID INT NOT NULL,
    Title VARCHAR(255) NOT NULL,
    Description TEXT NOT NULL,
    DateSubmitted DATETIME DEFAULT CURRENT_TIMESTAMP,
    SubmittedBy INT,
    FOREIGN KEY (IssueID) REFERENCES Issue(IssueID) ON DELETE CASCADE,
    FOREIGN KEY (SubmittedBy) REFERENCES User(UserID) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 7. Petition Table
CREATE TABLE Petition (
    PetitionID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255) NOT NULL,
    Description TEXT NOT NULL,
    DateCreated DATETIME DEFAULT CURRENT_TIMESTAMP,
    CreatedBy INT,
    Status ENUM('Open', 'Closed') DEFAULT 'Open',
    FOREIGN KEY (CreatedBy) REFERENCES User(UserID) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 8. Poll Table
CREATE TABLE Poll (
    PollID INT AUTO_INCREMENT PRIMARY KEY,
    Question VARCHAR(255) NOT NULL,
    DateCreated DATETIME DEFAULT CURRENT_TIMESTAMP,
    CreatedBy INT,
    IsActive BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (CreatedBy) REFERENCES User(UserID) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 9. PollOption Table
CREATE TABLE PollOption (
    OptionID INT AUTO_INCREMENT PRIMARY KEY,
    PollID INT NOT NULL,
    OptionText VARCHAR(255) NOT NULL,
    FOREIGN KEY (PollID) REFERENCES Poll(PollID) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 10. Vote Table
CREATE TABLE Vote (
    VoteID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    PollID INT NOT NULL,
    OptionID INT NOT NULL,
    DateVoted DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES User(UserID) ON DELETE CASCADE,
    FOREIGN KEY (PollID) REFERENCES Poll(PollID) ON DELETE CASCADE,
    FOREIGN KEY (OptionID) REFERENCES PollOption(OptionID) ON DELETE CASCADE,
    UNIQUE (UserID, PollID)
) ENGINE=InnoDB;

-- 11. PetitionSignature Table
CREATE TABLE PetitionSignature (
    SignatureID INT AUTO_INCREMENT PRIMARY KEY,
    PetitionID INT NOT NULL,
    UserID INT NOT NULL,
    DateSigned DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (PetitionID) REFERENCES Petition(PetitionID) ON DELETE CASCADE,
    FOREIGN KEY (UserID) REFERENCES User(UserID) ON DELETE CASCADE,
    UNIQUE (PetitionID, UserID)
) ENGINE=InnoDB;

-- 12. EmergencyPost Table
CREATE TABLE EmergencyPost (
    EmergencyID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255) NOT NULL,
    Description TEXT NOT NULL,
    Type ENUM('Natural Disaster', 'Health Crisis', 'Urgent Social Issue') NOT NULL,
    Location VARCHAR(255),
    DateSubmitted DATETIME DEFAULT CURRENT_TIMESTAMP,
    SubmittedBy INT,
    FOREIGN KEY (SubmittedBy) REFERENCES User(UserID) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 13. Notification Table
CREATE TABLE Notification (
    NotificationID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    Content TEXT NOT NULL,
    DateCreated DATETIME DEFAULT CURRENT_TIMESTAMP,
    IsRead BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (UserID) REFERENCES User(UserID) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 14. Comment Table
CREATE TABLE Comment (
    CommentID INT AUTO_INCREMENT PRIMARY KEY,
    Content TEXT NOT NULL,
    DateSubmitted DATETIME DEFAULT CURRENT_TIMESTAMP,
    SubmittedBy INT NOT NULL,
    EntityType ENUM('Issue', 'Solution', 'Petition') NOT NULL,
    EntityID INT NOT NULL,
    FOREIGN KEY (SubmittedBy) REFERENCES User(UserID) ON DELETE CASCADE
    -- Note: EntityID references multiple tables; foreign key constraints cannot be enforced here
) ENGINE=InnoDB;

-- 15. Upvote Table
CREATE TABLE Upvote (
    UpvoteID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    EntityType ENUM('Issue', 'Solution') NOT NULL,
    EntityID INT NOT NULL,
    DateUpvoted DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES User(UserID) ON DELETE CASCADE,
    UNIQUE (UserID, EntityType, EntityID)
    -- Note: EntityID references multiple tables; foreign key constraints cannot be enforced here
) ENGINE=InnoDB;

-- 16. Message Table (Optional)
CREATE TABLE Message (
    MessageID INT AUTO_INCREMENT PRIMARY KEY,
    SenderID INT NOT NULL,
    ReceiverID INT NOT NULL,
    Content TEXT NOT NULL,
    DateSent DATETIME DEFAULT CURRENT_TIMESTAMP,
    IsRead BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (SenderID) REFERENCES User(UserID) ON DELETE CASCADE,
    FOREIGN KEY (ReceiverID) REFERENCES User(UserID) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Insert data into User table
INSERT INTO User (username, password, Email, Role, ProfilePicture, DateRegistered, LastLogin, LanguagePreference)
VALUES
('john_doe', 'hashed_password_1', 'john@example.com', 'User', 'profile1.jpg', NOW(), NOW(), 'English'),
('jane_smith', 'hashed_password_2', 'jane@example.com', 'User', 'profile2.jpg', NOW(), NOW(), 'English'),
('michael_admin', 'hashed_password_3', 'michael@example.com', 'Admin', 'profile3.jpg', NOW(), NOW(), 'English'),
('anna_user', 'hashed_password_4', 'anna@example.com', 'User', 'profile4.jpg', NOW(), NOW(), 'Bengali'),
('tom_admin', 'hashed_password_5', 'tom@example.com', 'Admin', 'profile5.jpg', NOW(), NOW(), 'Bengali');

-- Insert data into Category table
INSERT INTO Category (Name, Description)
VALUES
('Infrastructure', 'Issues related to physical infrastructure like roads, bridges, and buildings.'),
('Corruption', 'Reports and issues related to corruption and misuse of power.'),
('Public Services', 'Issues related to government and municipal services like water, electricity, etc.'),
('Environment', 'Problems related to the environment, pollution, or natural resources.'),
('Health', 'Health-related issues such as hospital services, disease outbreaks, etc.');

-- Insert data into GovernmentProject table
INSERT INTO GovernmentProject (Title, Description, Budget, StartDate, EndDate, Progress, Status)
VALUES
('Dhaka Metro Expansion', 'Expanding the metro line in Dhaka city.', 50000000.00, '2023-01-01', '2024-12-31', 60, 'On Track'),
('Chittagong Water Supply', 'Improving water supply to Chittagong city.', 30000000.00, '2023-05-01', '2024-05-01', 30, 'Delayed'),
('Flood Control in Sylhet', 'Construction of flood control mechanisms in Sylhet.', 20000000.00, '2022-08-01', '2023-12-31', 90, 'On Track'),
('Healthcare Upgrade in Dhaka', 'Upgrading healthcare facilities in Dhaka.', 15000000.00, '2023-06-01', '2024-06-01', 40, 'On Track'),
('Roads Improvement in Khulna', 'Repairing major roads in Khulna.', 12000000.00, '2023-09-01', '2024-03-31', 20, 'Delayed');

-- Insert data into Issue table
INSERT INTO Issue (Title, Description, CategoryID, Location, DateSubmitted, Status, SubmittedBy)
VALUES
('Potholes in Dhaka', 'Major potholes on the main road are causing traffic jams.', 1, 'Dhaka', NOW(), 'Open', 1),
('Corruption in Public Hospital', 'Reports of corruption and malpractice in a local hospital.', 2, 'Chittagong', NOW(), 'In Progress', 2),
('Electricity Outages in Sylhet', 'Frequent power cuts affecting daily life.', 3, 'Sylhet', NOW(), 'Open', 3),
('Air Pollution in Dhaka', 'Increasing pollution levels affecting public health.', 4, 'Dhaka', NOW(), 'Resolved', 4),
('Water Supply Issues in Khulna', 'Residents are facing severe water shortages.', 3, 'Khulna', NOW(), 'In Progress', 1);

-- Insert data into WhistleblowerReport table
INSERT INTO WhistleblowerReport (Description, CategoryID, DateSubmitted, Attachments, TrackingCode, Status)
VALUES
('Corruption in local government office.', 2, NOW(), 'evidence1.pdf', 'WB1234', 'New'),
('Bribery in Dhaka customs.', 2, NOW(), 'evidence2.pdf', 'WB5678', 'Under Review'),
('Mismanagement of public funds in Chittagong.', 2, NOW(), 'evidence3.pdf', 'WB9101', 'Resolved'),
('Illegal construction in protected area.', 4, NOW(), 'evidence4.pdf', 'WB1121', 'New'),
('Violation of health codes in public hospital.', 5, NOW(), 'evidence5.pdf', 'WB3141', 'Under Review');

-- Insert data into Solution table
INSERT INTO Solution (IssueID, Title, Description, DateSubmitted, SubmittedBy)
VALUES
(1, 'Road Repair Proposal', 'Proposal to repair the potholes in Dhaka using high-quality materials.', NOW(), 1),
(2, 'Hospital Corruption Monitoring', 'Suggestion to install CCTV cameras in public hospitals.', NOW(), 2),
(3, 'Electricity Backup Generators', 'Provide backup generators for areas facing frequent power cuts.', NOW(), 3),
(4, 'Pollution Control in Dhaka', 'Install air purifiers and reduce vehicle emissions.', NOW(), 4),
(5, 'Improve Water Infrastructure', 'Invest in improving the water pipeline system in Khulna.', NOW(), 5);

-- Insert data into Petition table
INSERT INTO Petition (Title, Description, DateCreated, CreatedBy, Status)
VALUES
('Improve Healthcare Facilities', 'Petition to improve healthcare facilities in rural areas.', NOW(), 1, 'Open'),
('Reduce Corruption in Government', 'Petition to introduce stricter anti-corruption laws.', NOW(), 2, 'Open'),
('Increase Public Transportation', 'Petition to improve public transportation in Dhaka.', NOW(), 3, 'Closed'),
('Clean Water Access', 'Petition to ensure clean water supply in urban areas.', NOW(), 4, 'Open'),
('Affordable Housing', 'Petition to create more affordable housing options in cities.', NOW(), 5, 'Open');

-- Insert data into Poll table
INSERT INTO Poll (Question, DateCreated, CreatedBy, IsActive)
VALUES
('Should Dhaka expand its metro line?', NOW(), 1, TRUE),
('Should the government invest more in healthcare?', NOW(), 2, TRUE),
('Should taxes be reduced for low-income families?', NOW(), 3, TRUE),
('Should corruption penalties be increased?', NOW(), 4, FALSE),
('Should the city plant more trees to reduce pollution?', NOW(), 5, TRUE);

-- Insert data into PollOption table
INSERT INTO PollOption (PollID, OptionText)
VALUES
(1, 'Yes'),
(1, 'No'),
(2, 'Yes'),
(2, 'No'),
(3, 'Yes'),
(3, 'No'),
(4, 'Yes'),
(4, 'No'),
(5, 'Yes'),
(5, 'No');

-- Insert data into Vote table
INSERT INTO Vote (UserID, PollID, OptionID, DateVoted)
VALUES
(1, 1, 1, NOW()),
(2, 1, 2, NOW()),
(3, 2, 1, NOW()),
(4, 2, 2, NOW()),
(5, 3, 1, NOW());

-- Insert data into PetitionSignature table
INSERT INTO PetitionSignature (PetitionID, UserID, DateSigned)
VALUES
(1, 1, NOW()),
(2, 2, NOW()),
(3, 3, NOW()),
(4, 4, NOW()),
(5, 5, NOW());

-- Insert data into EmergencyPost table
INSERT INTO EmergencyPost (Title, Description, Type, Location, DateSubmitted, SubmittedBy)
VALUES
('Flood Warning in Sylhet', 'A major flood is expected to hit Sylhet in the next 48 hours.', 'Natural Disaster', 'Sylhet', NOW(), 1),
('Cholera Outbreak in Dhaka', 'A cholera outbreak has been reported in parts of Dhaka.', 'Health Crisis', 'Dhaka', NOW(), 2),
('Road Blockage in Chittagong', 'Roads are blocked due to a landslide in Chittagong.', 'Urgent Social Issue', 'Chittagong', NOW(), 3),
('Cyclone Alert in Khulna', 'A cyclone alert has been issued for Khulna.', 'Natural Disaster', 'Khulna', NOW(), 4),
('Water Contamination in Dhaka', 'Water contamination in certain areas of Dhaka.', 'Health Crisis', 'Dhaka', NOW(), 5);

-- Insert data into Notification table
INSERT INTO Notification (UserID, Content, DateCreated, IsRead)
VALUES
(1, 'Your petition has been approved.', NOW(), FALSE),
(2, 'A new vote has been recorded for your poll.', NOW(), TRUE),
(3, 'The issue you reported is now under review.', NOW(), FALSE),
(4, 'Your whistleblower report is now being processed.', NOW(), TRUE),
(5, 'A new emergency alert has been posted.', NOW(), FALSE);

-- Insert data into Comment table
INSERT INTO Comment (Content, DateSubmitted, SubmittedBy, EntityType, EntityID)
VALUES
('This is a critical issue, and we need immediate action.', NOW(), 1, 'Issue', 1),
('I support this solution, it’s practical.', NOW(), 2, 'Solution', 1),
('The petition is important for our future.', NOW(), 3, 'Petition', 1),
('We should prioritize healthcare.', NOW(), 4, 'Issue', 2),
('Great job on resolving the issue!', NOW(), 5, 'Solution', 2);

-- Insert data into Upvote table
INSERT INTO Upvote (UserID, EntityType, EntityID, DateUpvoted)
VALUES
(1, 'Issue', 1, NOW()),
(2, 'Solution', 1, NOW()),
(3, 'Issue', 2, NOW()),
(4, 'Solution', 2, NOW()),
(5, 'Issue', 3, NOW());

-- Insert data into Message table (Optional)
INSERT INTO Message (SenderID, ReceiverID, Content, DateSent, IsRead)
VALUES
(1, 2, 'Can we discuss the issue you reported?', NOW(), FALSE),
(2, 3, 'Please review the new solution proposal.', NOW(), TRUE),
(3, 4, 'I need more information on the petition.', NOW(), FALSE),
(4, 5, 'Let’s coordinate on the emergency alert.', NOW(), TRUE),
(5, 1, 'Your whistleblower report has been updated.', NOW(), FALSE);
