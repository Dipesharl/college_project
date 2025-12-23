-- Create Database
CREATE DATABASE IF NOT EXISTS blogmandu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blogmandu;

-- Categories Table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    profile_image VARCHAR(255) DEFAULT 'default-avatar.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Posts Table
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category_id INT NOT NULL,
    author_id INT NOT NULL,
    image VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert Default Categories
INSERT INTO categories (name) VALUES 
    ('Temples'),
    ('Travel'),
    ('Food');

-- Insert Admin User (password: admin123)
INSERT INTO users (username, email, password, full_name, role, profile_image) VALUES 
    ('admin', 'admin@blogmandu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin', 'profile.jpeg');

-- Insert Sample User (password: user123)
INSERT INTO users (username, email, password, full_name, role, profile_image) VALUES 
    ('dipesharl', 'dipesh@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dipesh Arl', 'user', 'profile.jpeg'),
    ('johnmayer', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Mayer', 'user', 'person.jpg'),
    ('jaindain', 'jain@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jain Dain', 'user', 'women.jpg');

-- Insert Sample Posts
INSERT INTO posts (title, content, category_id, author_id, image, is_featured) VALUES 
    ('Pashupatinath Temple', 
     'Pashupatinath Temple is a revered Hindu temple dedicated to Pashupati, a manifestation of Shiva. Located on the banks of the sacred Bagmati River in Kathmandu, Nepal, the temple is one of the oldest and most significant religious complexes in South Asia. Recognised as a UNESCO World Heritage Site since 1979, it is one of seven monument groups in UNESCO\'s designation of Kathmandu Valley and is described as an "extensive Hindu temple precinct" comprising a vast network of temples, ashrams, inscriptions, and images raised over the centuries along the banks of the sacred Bagmati river. The temple, considered one of the holiest pilgrimage sites for Hindus, is built on an area of 246 hectares and includes 518 mini-temples and the principal pagoda-style temple.',
     1, 2, 'Pashupatinath_Temple-2020.jpg', TRUE),
    
    ('Swayambhunath Temple', 
     'Swayambhunath is an ancient religious complex atop a hill in the Kathmandu Valley, west of Kathmandu city. The Tibetan and Sanskrit name for the site means "self-arising" or "self-sprung". The hill on which the stupa stands has been an ancient pilgrimage place considered the home of the primordial Buddha known as the Adi-Buddha. For the Buddhists throughout the world, the stupa is venerated as one of the most ancient and important stupas in the world, having hosted numerous Buddhas of the past. For its outstanding universal value, Swayambhunath was designated a UNESCO World Heritage Site in Nepal in 1979.',
     1, 3, 'Swayambhunath.jpg', FALSE),
    
    ('Mustang', 
     'Mustang District is one of the eleven districts of Gandaki Province and one of seventy-seven districts of Nepal which was a Kingdom of Lo-Manthang that joined the Federation of Nepal in 2008 after abolition of the Shah dynasty. The district covers an area of 3,573 km² and in 2021 had a population of 14,452. The headquarter is located at Jomsom. Mustang is the fifth largest district of Nepal in terms of area. The district is home to Muktinath Temple and is a sacred place for Hindus and Buddhists. The district is a part of Gandaki Province in northern Nepal, straddles the Himalayas and extends northward onto the Tibetan Plateau.',
     2, 4, 'Kagdeni,_Mustang,_Nepal.jpg', FALSE),
    
    ('Dal Bhat - A Nepali Thali', 
     'It is often said that brilliance lies in simplicity. Nothing exemplifies this better than the Nepali Dal-Bhat thali, that is in its essence a simple concept, but is a food adventure when experienced. The Dal-Bhat is an Indian takeaway, and consists of thick lentil soup, usually black lentils or beans, along with puffy, soft rice. The thali, which is a steel tray, is designed with many little compartments for soups and veggies. The dish is typically composed of dal (lentil soup), bhat (rice) and tarkari (spiced dry/gravy vegetables). The thali is accompanied by a selection of chutney and pickles, giving the dal-bhat-tarkari combination an epic kick of spice and salt. Dal-Bhat is widespread and so dear to Nepalis that it is often considered the country\'s "national food".',
     3, 4, 'dal-bhat-tarkari.jpg', FALSE),
    
    ('Budhanilkantha Temple', 
     'Budanilkantha Temple, located in Budhanilkantha, Nepal, is a Hindu open air temple dedicated to Lord Mahavishnu. Budhanilkantha Temple is also known as the Narayanthan Temple for Hindus, and can be identified by a large reclining statue of Lord Mahavishnu. However In the Buddhist community, the term "Buddhanilkantha" refers to one of the various manifestations of Avalokiteshvara. The name means "Blue-Throated Buddha."',
     1, 3, 'Budhanilkantha_Narayan_Murti_(2023)_-_IMG_03.jpg', FALSE);