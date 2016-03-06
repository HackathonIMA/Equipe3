# This file should contain all the record creation needed to seed the database with its default values.
# The data can then be loaded with the rake db:seed (or created alongside the db with db:setup).
#
# Examples:
#
#   cities = City.create([{ name: 'Chicago' }, { name: 'Copenhagen' }])
#   Mayor.create(name: 'Emanuel', city: cities.first)

# TODO: load the schools!

begin
  users = User.create([
                        {
                          name: 'Chaves',
                          email: 'chaves@vila.com.mx',
                          birthday: Time.new(1988, 12, 15),
                          address: 'No Barril'
                        },
                        {
                          name: 'Chiquinha',
                          email: 'chiquinha@vila.com.mx',
                          birthday: Date.new(1990, 02, 5),
                          address: 'Apartamento 42'
                        },
                        {
                          name: 'Seu Madruga',
                          email: 'seu.madruga@vila.com.mx',
                          birthday: Date.new(1970, 08, 27),
                          address: 'Apartamento 42'
                        },
                        {
                          name: 'Professor Girafales',
                          email: 'prof.girafales@escola.com.mx',
                          birthday: Date.new(1968, 08, 27)
                        }
                      ])
rescue
  puts "Error saving users... continuing"
end

if School.all.length <= e_length
  e_length = 50
  ima = ImaApi.new('O4FK6qtxiu4m')
  ima_escolas = ima.escolas(0, e_length)
  e_length.times do |i|
    School.create([{ima_id: ima_escolas[i]['id']}])
  end
end

begin
  shares = Shares.create([{
    title: "Desperdício de água do banheiro",
    description: "Um cano está quebrado próximo à cantina.",
    category: 1,
    school_id: 5,
    user_id: users[0].id
  }, {
    title: "Reunião de pais e mestres",
    description: "Na próxima semana teremos a reunião de pais.",
    category: 2,
    school_id: 7,
    user_id: users[3].id
  }])
rescue
  puts "Error saving events... continuing"
end
