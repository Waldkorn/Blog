var currentContent = "";

function navigateTo(contentToDisplay) {

	if (currentContent != contentToDisplay) {

		if (currentContent != "") {
			$("#" + currentContent).toggle('slow');
		}

		$("#" + contentToDisplay).toggle('slow');

		currentContent = contentToDisplay;

	}

}