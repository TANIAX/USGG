CKEDITOR.plugins.add( 'upperlower',
{
	init: function( editor )
	{
		editor.addCommand( 'upper',
        {
            exec : function( editor )
            {                
                var selection = editor.getSelection();
                editor.insertHtml( selection.getSelectedText().toUpperCase() );
            }
        });
        
        editor.addCommand( 'lower',
        {
            exec : function( editor )
            {                
                var selection = editor.getSelection();
                editor.insertHtml( selection.getSelectedText().toLowerCase() );
            }
        });

        editor.addCommand( 'RemoveBgColor',
        {
            exec : function( editor )
            {             
                //On retire la couleur de fond, ça parait simple comme ça mais ça m'a pris du temps de trouver ça.... beaucoup de temps...    
                var selection = editor.getSelection();
                var startElement = selection.getStartElement().$;
                if(startElement){
                    startElement.style.backgroundColor = 'white';
                }
            }
        });
        
        
        editor.ui.addButton( 'Upper',
        {
            label: 'Maj',
            command: 'upper',
            icon: this.path + 'images/maj.png'
        } );
        
        editor.ui.addButton( 'Lower',
        {
            label: 'Min',
            command: 'lower',
            icon: this.path + 'images/min.png'
        } );

        editor.ui.addButton( 'RemoveBgColor',
        {
            label: 'Retirer la couleur de fond (Ctrl+Q)',
            command: 'RemoveBgColor',
            icon: this.path + 'images/remove-bg-color.png',
            //Shortcut
            keystroke: CKEDITOR.CTRL  + 81
        });
        
        editor.ui.addRichCombo("Injection",
                {
                    label: "Injection",
                    title: "Injection",
                    className: "cke_format",
                    multiSelect: false,
                    panel:
                    {
                        css: [editor.config.contentsCss, CKEDITOR.skin.getPath("editor")],
                        voiceLabel: editor.lang.panelVoiceLabel
                    },

                    init: function () {
                        this.startGroup("Personne concernée");
                        this.add("{{POLITESSE}}", "Politesse");
                        this.add("{{NOM}}", "Nom");
                        this.add("{{PRENOM}}", "Prénom");
                        this.add("{{FONCTION}}", "Fonction");
                        this.add("{{TYPE_CONTRAT}}", "Type de contrat");
                        this.add("{{Service}}", "Service");
                    },

                    onClick: function (value) {
                        editor.focus();
                        editor.fire("saveSnapshot");
                        editor.insertHtml(value);
                        editor.fire("saveSnapshot");
                    }
                });
	}
} );
