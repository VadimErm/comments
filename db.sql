--
-- Table structure for table `countries`
--

CREATE TABLE `comments` (
  `id` INT(11) NOT NULL,
  `user_id` INT(10) NOT NULL,
  `text` TEXT(300) NOT NULL ,
  `created_at` INT(11) NOT NULL ,
  `updated_at` INT(11)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `countries`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;