import { TestBed } from '@angular/core/testing';
import { provideRouter } from '@angular/router';
import { ProfesoresPage } from './profesores-page';

describe('ProfesoresPage', () => {
  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ProfesoresPage],
      providers: [provideRouter([])],
    }).compileComponents();
  });

  it('should render the profesores base layout', () => {
    const fixture = TestBed.createComponent(ProfesoresPage);
    fixture.detectChanges();

    const compiled = fixture.nativeElement as HTMLElement;
    expect(compiled.querySelector('h1')?.textContent).toContain('Profesores');
    expect(compiled.textContent).toContain('Padrón de profesores');
    expect(compiled.textContent).toContain('Registrar profesor');
  });
});
