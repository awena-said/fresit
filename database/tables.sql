-- Staff Table (for tutors mentioned in cohorts)
CREATE TABLE IF NOT EXISTS `staff_users` (
    `id` VARCHAR(255) PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL
);

-- Students Table (for optional student accounts)
CREATE TABLE IF NOT EXISTS `students` (
    `id` VARCHAR(255) PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `phone` VARCHAR(50),
    `password` VARCHAR(255) NOT NULL
);

-- Classes Table (for class sessions with time slots and tutors)
CREATE TABLE IF NOT EXISTS `classes` (
    `id` VARCHAR(255) PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `type` ENUM('Foundation', 'Imagination', 'Watercolour') NOT NULL,
    `date` DATE NOT NULL,
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `tutor_id` VARCHAR(255) NOT NULL,
    `capacity` INT NOT NULL DEFAULT 20,
    FOREIGN KEY (`tutor_id`) REFERENCES `staff_users`(`id`) ON DELETE CASCADE
);

-- Applications Table (for student applications)
CREATE TABLE IF NOT EXISTS `applications` (
    `id` VARCHAR(255) PRIMARY KEY,
    `class_id` VARCHAR(255) NOT NULL,
    `student_id` VARCHAR(255) NULL,
    `student_name` VARCHAR(255) NOT NULL,
    `student_email` VARCHAR(255) NOT NULL,
    `student_phone` VARCHAR(50) NOT NULL,
    `status` ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE SET NULL
); 