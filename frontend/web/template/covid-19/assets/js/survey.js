survey = { questions: undefined,
           firstQuestionDisplayed: -1,
           lastQuestionDisplayed: -1};

(function (survey, $) {

    survey.setup_survey = function(questions) {
        var self = this;
        this.questions = questions;

        this.questions.forEach(function(question) {
            self.generateQuestionElement( question );
        });
      
        $('#backBtn').click(function() {
            if ( !$('#backBtn').hasClass('disabled') ) {
                self.showPreviousQuestionSet();
            }
        });
      
        $('#nextBtn').click(function() {
            var ok = true;
            for (i = self.firstQuestionDisplayed; i <= self.lastQuestionDisplayed; i++) {
                if (self.questions[i]['required'] === true && !self.getQuestionAnswer(questions[i])) {
                    $('.question-container > div.question:nth-child(' + (i+1) + ') > .required-message').show();
                    ok = false;
                }
            }
            if (!ok)
                return

            if ( $('#nextBtn').text().indexOf('Continue') === 0 ) {
                self.showNextQuestionSet();
            }
            else {
                var answers = {res: $(window).width() + "x" + $(window).height()};
                for (i = 0; i < self.questions.length; i++) {
                    answers[self.questions[i].id] = self.getQuestionAnswer(self.questions[i]);
                }

                $.ajax({type: 'post',
                        url: 'http://localhost:7000/answers',
                        contentType: "application/json",
                        data: JSON.stringify(answers),
                        processData: false,
                        success: function(response) {
                            self.hideAllQuestions();
                            $('#nextBtn').hide();
                            $('#backBtn').hide();
                            if ('success' in response) {
                                $('.completed-message').html('Thank you for participating in this survey!<br><br>'+response['success']);
                            }
                            else if ('error' in response) {
                                $('.completed-message').text('An error occurred: '+response['error']);
                            }
                            else {
                                $('.completed-message').text('An unknown error occurred.');
                            }
                        },
                        error: function(response) {
                            self.hideAllQuestions();
                            $('#nextBtn').hide();
                            $('#backBtn').hide();
                            $('.completed-message').text('An error occurred: could not send data to server');
                        }
                });
            }
        });
      
        this.showNextQuestionSet();
         
    }

    survey.getQuestionAnswer = function(question) {
        var result;
        if ( question.type === 'single-select' ) {
            result = $('input[type="radio"][name="' + question.id + '"]:checked').index();
        }
        else if ( question.type === 'single-select-oneline' ) {
            result = $('input[type="radio"][name="' + question.id + '"]:checked').val();
        }
        else if ( question.type === 'text-field-small' ) {
            result = $('input[name=' + question.id + ']').val();
        }
        else if ( question.type === 'text-field-large' ) {
            result = $('textarea[name=' + question.id + ']').val();
        }
        return result ? result : undefined;
    }

    survey.generateQuestionElement = function(question) {
        var questionElement = $('<div id="' + question.id + '" class="question"></div>');
        var questionTextElement = $('<div class="question-text"></div>');
        var questionAnswerElement = $('<div class="answer"></div>');
        var questionCommentElement = $('<div class="comment"></div>');
        questionElement.appendTo($('.question-container'));
        questionElement.append(questionTextElement);
        questionElement.append(questionAnswerElement);
        questionElement.append(questionCommentElement);
        questionTextElement.html(question.text);
        questionCommentElement.html(question.comment);
        var i = 0;
        if ( question.type === 'single-select' ) {
            questionElement.addClass('single-select');
            question.options.forEach(function(option) {
                
                questionAnswerElement.append('<label class="radio"><input type="radio" value="' + i + '" name="' + question.id + '"/>' + option + '</label>');
                i++;
            });
        }
        else if ( question.type === 'single-select-oneline' ) {
            questionElement.addClass('single-select-oneline');
            var html = '<table border="0" cellpadding="5" cellspacing="0"><tr><td></td>';
            question.options.forEach(function(label) {
                html += '<td><label>' + label + '</label></td>';
            });
            html += '<td></td></tr><tr><td><div>' + question.labels[0] + '</div></td>';
            question.options.forEach(function(label) {
                html += '<td><div><input type="radio" value="' + label + '" name="' + question.id + '"></div></td>';
            });
            html += '<td><div>' + question.labels[1] + '</div></td></tr></table>';
            questionAnswerElement.append(html);
        }
        else if ( question.type === 'text-field-small' ) {
            questionElement.addClass('text-field-small');
            questionAnswerElement.append('<input type="text" value="" class="text" name="' + question.id + '">');
        }
        else if ( question.type === 'text-field-large' ) {
            questionElement.addClass('text-field-large');
            questionAnswerElement.append('<textarea rows="8" cols="0" class="text" name="' + question.id + '">');
        }
        if ( question.required === true ) {
            var last = questionTextElement.find(':last');
            (last.length ? last : questionTextElement).append('<span class="required-asterisk" aria-hidden="true">*</span>');
        }
        questionAnswerElement.after('<div class="required-message">จำเป็นต้องระบุ</div>');
        questionElement.hide();
    }

    survey.hideAllQuestions = function() {
        $('.question:visible').each(function(index, element){
            $(element).hide();
        });
        $('.required-message').each(function(index, element){
            $(element).hide();
        });
    }

    survey.showNextQuestionSet = function() {
        this.hideAllQuestions();
        this.firstQuestionDisplayed = this.lastQuestionDisplayed+1;
      
        do {
            this.lastQuestionDisplayed++;  
            $('.question-container > div.question:nth-child(' + (this.lastQuestionDisplayed+1) + ')').show();
            if ( this.questions[this.lastQuestionDisplayed]['break_after'] === true)
                break;
        } while ( this.lastQuestionDisplayed < this.questions.length-1 );
      
        this.doButtonStates();
    }

    survey.showPreviousQuestionSet = function() {
        this.hideAllQuestions();
        this.lastQuestionDisplayed = this.firstQuestionDisplayed-1;
      
        do {
            this.firstQuestionDisplayed--;  
            $('.question-container > div.question:nth-child(' + (this.firstQuestionDisplayed+1) + ')').show();
            if ( this.firstQuestionDisplayed > 0 && this.questions[this.firstQuestionDisplayed-1]['break_after'] === true)
                break;
        } while ( this.firstQuestionDisplayed > 0 );
      
        this.doButtonStates();
    }

    survey.doButtonStates = function() {
        if ( this.firstQuestionDisplayed == 0 ) {
            $('#backBtn').addClass('invisible');  
        }
        else if ( $('#backBtn' ).hasClass('invisible') ) {
            $('#backBtn').removeClass('invisible');
        }
        
        if ( this.lastQuestionDisplayed == this.questions.length-1 ) {
            $('#nextBtn').text('ดูผลลัพธ์การประเมิน');
            $('#nextBtn').addClass('blue');  
        }
        else if ( $('#nextBtn').text() === 'ดูผลลัพธ์การประเมิน' ) {
            $('#nextBtn').text('Continue »'); 
            $('#nextBtn').removeClass('blue');
        }
    }
})(survey, jQuery);


$(document).ready(function(){
    //survey.setup_survey($.parseJSON(''));
    // $.getJSON('questions.json', function(json) {
         survey.setup_survey([  
    {  
        "text":"ข้อที่ 1 : มีไข้สูง 37.5 องศา (Celsius) ขึ้นไป หรือ รู้สึกว่ามีไข้?",
        "id":"2",
        "break_after":false,
        "required":true,
        "type":"single-select",
        "options":[  
            "ต่ำกว่า 37.5",
            "สูงกว่าหรือเท่ากับ 37.5 หรือ รู้สึกว่ามีไข้",
        ]
    },
    {  
        "text":"ข้อที่ 2 : มีอาการอย่างหนึ่งในนี้ ( ไอ เจ็บคอ หอบเหนื่อยผิดปกติ มีน้ำมูก )?",
        "id":"3",
        "required":true,
        "type":"single-select",
        "options":[  
            "ไม่มี",
            "มี",
        ]
    },
    {  
        "text":"ข้อที่ 3 : มีประวัติเดินทางไปประเทศกลุ่มเสี่ยงหรือพื้นที่เสี่ยงตามประกาศกรมในช่วง 14 วันก่อน? (ตรวจสอบได้ที่นี่)",
        "id":"4",
        "required":true,
        "type":"single-select",
        "options":[  
            "ไม่มี",
            "มี",
        ]
    },
    {  
        "text":"ข้อที่ 4 : มีประวัติอยู่ใกล้ชิดกับผู้ป่วยยืนยัน COVID-19 (ใกล้กว่า 1 เมตร นานเกิน 5 นาที) ในช่วง 14 วันก่อน หรือ ไปสนามมวยลุมพินี หรือ ผับที่มีการพบผู้ติดเชื้อ?",
        "id":"5",
        "required":true,
        "type":"single-select",
        "options":[  
            "ไม่มี",
            "มี",
        ]
    },
    {  
        "text":"ข้อที่ 5 : มีบุคคลในบ้านเดินทางไปประเทศกลุ่มเสี่ยงหรือพื้นที่เสี่ยงตามประกาศกรมในช่วง 14 วันก่อน? (ตรวจสอบได้ที่นี่)",
        "id":"6",
        "required":true,
        "type":"single-select",
        "options":[  
            "ไม่มี",
            "มี",
        ]
    },
    {  
        "text":"ข้อที่ 6 : ประกอบอาชีพใกล้ชิดกับชาวต่างชาติ?",
        "id":"7",
        "required":true,
        "type":"single-select",
        "options":[  
            "ไม่ใช่",
            "ใช่",
        ]
    },
    {  
        "text":"ข้อที่ 7 : เป็นบุคลากรทางการแพทย์?",
        "id":"8",
        "required":true,
        "type":"single-select",
        "options":[  
            "ไม่ใช่",
            "ใช่",
        ]
    },
    {  
        "text":"ข้อที่ 8 : มีผู้ใกล้ชิดป่วยเป็นไข้หวัดพร้อมกัน มากกว่า 5 คน ในช่วง 14 วันก่อน?",
        "id":"9",
        "required":true,
        "type":"single-select",
        "options":[  
            "ไม่มี",
            "มี",
        ]
    }
]);        
    // });
});

window.onbeforeunload = function() {
    return "This will reset all answers that you've already filled in!";
}