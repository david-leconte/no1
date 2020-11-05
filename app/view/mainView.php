<!DOCTYPE html>
<html>
    <head>
    	<meta charset="utf-8" />
    	<title><?php echo App::siteName; ?></title>
		<link rel="stylesheet" href="res/sass/main.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    </head>

	<body>
		<form name="search" id="search" action="" method="post">
			<a href="<?php echo str_replace('index.php', '', $_SERVER['PHP_SELF']); ?>"><?php echo App::siteName; ?></a>
			<input id="search-input" type="search" placeholder="#SomeRandomPlace @SomeRandomPerson" name="search" />
			<input type="hidden" id="last-seen" name="last-seen-msg" />
			<input type="hidden" name="json" value="1" />
			<button class="reload"><i class="fas fa-sync"></i></button>
		</form>

		<main>
			<?php 
			// MESSAGES RENDERED IN JS
			/* foreach($messages as $message) { 
			?>
			<article>
				<div class="sticker"></div>
				<button class="author"><?php echo $message['username']; ?></button>
				<p class="right-infos">
					<i class="message-datetime"><?php echo $message['datetime']; ?></i>
					<button class="delete"><i class="fas fa-trash"></i></button>
				</p>

				<p class="text"><?php echo $message['text']; ?></p>

				<button class="upvote"><i class="fas fa-chevron-up"></i></button>
				<button class="downvote"><i class="fas fa-chevron-down"></i></button>

				<a href="?message=<?php echo $message['id']; ?>">Copy link</a>
			</article>
			<?php 
			} 
			*/?>
		</main>

		<form name="create" id="create" action="" method="post">
			<label for="text">Identifier : <span id="user-id"><?php echo $this->model->getUsernameInfo()['username']; ?></span></label>
			<textarea id="text" name="new-message" placeholder="Write something anonymously"></textarea>

			<input type="submit" value="Send" />
		</form>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
		<script src="res/js/app.js?<?php echo time(); ?>"></script>
	</body>
</html>