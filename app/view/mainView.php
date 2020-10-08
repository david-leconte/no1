<!DOCTYPE html>
<html>
    <head>
    	<meta charset="utf-8" />
    	<title><?php echo App::siteName; ?></title>
		<link rel="stylesheet" href="res/sass/dist/main.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    </head>

	<body>
		<form name="search" id="search" action="">
			<a href="<?php echo $_SERVER['PHP_SELF']; ?>"><?php echo App::siteName; ?></a>
			<input id="search-input" type="search" placeholder="#SomeRandomPlace @SomeRandomPerson" />
		</form>

		<main>
			<?php foreach($messages as $message) { ?>
			<article class="<?php echo $this->colorFromUsername($message['username']); ?>">
				<div class="sticker <?php echo $this->colorFromUsername($message['username']); ?>"></div>

				<button class="author"><?php echo $message['username']; ?></button>
				<p class="right-infos">
					<i class="message-datetime"><?php echo $message['datetime']; ?></i>
					<button class="delete"><i class="fas fa-trash"></i></button>
				</p>

				<p><?php echo $message['text']; ?></p>

				<button class="upvote"><i class="fas fa-chevron-up"></i></button>
				<button class="downvote"><i class="fas fa-chevron-down"></i></button>
				<button class="other"><i class="fas fa-yin-yang"></i></button>

				<a href="?message=<?php echo $message['id']; ?>">Share link</a>
			</article>
			<?php } ?>
		</main>

		<form name="create" id="create" action="" method="post">
			<label for="text">Identifier : <?php echo $this->model->getUsernameInfo()['username']; ?></label>
			<textarea id="text" name="message" placeholder="Write something anonymously"></textarea>

			<input type="submit" value="Send" />
		</form>
	</body>
</html>