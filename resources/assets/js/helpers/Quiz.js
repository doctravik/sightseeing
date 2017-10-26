class Quiz
{
    /**
     * Create an instance of Quiz.
     *
     * @param  {Number} min
     * @param  {Number} max
     */
    constructor(min, max) {
       this.current = min;
       this.min = min;
       this.max = max;
    }

    /**
     * Move to the next step.
     */
    next() {
        if (this.hasNextStep()) {
            this.setCurrent(this.current + 1);
        }
    }

    /**
     * Move to the previous step.
     */
    prev() {
        if (this.hasPreviousStep()) {
            this.setCurrent(this.current - 1);
        }
    }

    /**
     * Update current step.
     *
     * @param {Number} step
     */
    setCurrent(step) {
        if (this.current < this.min || this.current > this.max) {
            this.current = this.min;
        } else {
            this.current = step;
        }
    }

    /**
     * Whether quiz has previous step.
     *
     * @return {Boolean}
     */
    hasPreviousStep() {
        return this.current > this.min;
    }

    /**
     * Whether quiz has next step.
     *
     * @return {Boolean}
     */
    hasNextStep() {
        return this.current < this.max;
    }
}

export default Quiz;
