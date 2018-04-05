describe('Smoke Test', () => {

    let host  = Cypress.env('host') || Cypress.config('host'),
        pages = Cypress.config('smoke-pages');

    pages.forEach(url => {
        it('Checks that page ' + host + url + ' has no fatal', () => {

            cy.visit(host + url);
            cy.get("#colophon").should('have.class', 'site-footer');

        });
    });

});
