import Quiz from './../helpers/Quiz';

export default {
    data() {
        return {
            quiz: new Quiz(1, 2),
        }
    },

    methods: {
        /**
         * Check current step.
         *
         * @param  {Integer} step
         * @return {Boolean}
         */
        isStep(step) {
            return this.quiz.current == step;
        },

        /**
         * Move quiz to the start step.
         */
        resetQuiz() {
            this.quiz.setCurrent(this.quiz.min);
        },

        /**
         * Move to the next step.
         */
        incrementStep() {
            this.quiz.next();
        },

        /**
         * Move to the previous step.
         */
        decrementStep() {
            this.quiz.prev();
        },

        /**
         * Check if quiz has previous step.
         *
         * @return {Boolean}
         */
        hasPreviousStep() {
            return this.quiz.hasPreviousStep();
        },

        /**
         * Check if quiz has next step.
         *
         * @return {Boolean}
         */
        hasNextStep() {
            return this.quiz.hasNextStep();
        }
    }
}
