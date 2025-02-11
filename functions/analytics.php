<?php
/* Users */
$query = "SELECT COUNT(*) FROM users WHERE user_access_level = 'System Administrator' || user_access_level = 'Executive'";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($users);
$stmt->fetch();
$stmt->close();

/* Facilities */
$query = "SELECT COUNT(*) FROM facilities";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($facilities);
$stmt->fetch();
$stmt->close();

/* Casuals */
$query = "SELECT COUNT(*) FROM casuals";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($casuals);
$stmt->fetch();
$stmt->close();

/* Facility Heads */
$query = "SELECT COUNT(*) FROM users WHERE user_access_level = 'MOH'";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($moh);
$stmt->fetch();
$stmt->close();
