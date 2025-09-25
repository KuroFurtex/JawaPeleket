<div class="container">
    <h2>Page Dialogue Test</h2>
    <p>This page demonstrates the dialogue functionality.</p>
    <button id="showDialogue1" class="btn btn-primary">Show Dialogue 1</button>
    <button id="showDialogue2" class="btn btn-primary">Show Dialogue 2</button>
    <button id="showDialogue3" class="btn btn-primary">Show Dialogue 3</button>
</div>

<script>
    // Create event listeners for the buttons
    document.getElementById('showDialogue1').addEventListener('click', function() {
        const dialogue = FurtexUtil.createPageDialogue(
            "Dialogue 1", 
            "<p>This is the first dialogue.</p>",
            [{ text: "OK" }]
        );
        dialogue.showModal();
    });

    document.getElementById('showDialogue2').addEventListener('click', function() {
        const dialogue = FurtexUtil.createPageDialogue(
            "Dialogue 2", 
            "<p>This is the second dialogue with multiple buttons.</p>",
            [
                { text: "Cancel" },
                { 
                    text: "Confirm", 
                    action: () => alert("Confirmed!") 
                }
            ]
        );
        dialogue.showModal();
    });

    document.getElementById('showDialogue3').addEventListener('click', function() {
        const dialogue = FurtexUtil.createPageDialogue(
            "Dialogue 3", 
            "<p>This is the third dialogue that stays open after action.</p>",
            [
                { text: "Close" },
                { 
                    text: "Action", 
                    action: () => alert("Action performed!"),
                    closeOnClick: false
                }
            ]
        );
        dialogue.showModal();
    });

    // Create three dialogues automatically when the page loads
    window.addEventListener('DOMContentLoaded', function() {
        // Create dialogue 1
        const dialogue1 = FurtexUtil.createPageDialogue(
            "Auto Dialogue 1", 
            "<p>This is an automatically created dialogue.</p>"
        );
        dialogue1.showModal();

        // Create dialogue 2 after a short delay
        setTimeout(() => {
            const dialogue2 = FurtexUtil.createPageDialogue(
                "Auto Dialogue 2", 
                "<p>This is the second automatically created dialogue.</p>"
            );
            dialogue2.showModal();
        }, 1000);

        // Create dialogue 3 after another delay
        setTimeout(() => {
            const dialogue3 = FurtexUtil.createPageDialogue(
                "Auto Dialogue 3", 
                "<p>This is the third automatically created dialogue.</p>"
            );
            dialogue3.showModal();
        }, 2000);
    });
</script>