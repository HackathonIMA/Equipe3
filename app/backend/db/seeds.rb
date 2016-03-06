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
                          birthday: Date.new(1990, 12, 15),
                          address: 'Apartamento 42'
                        }
                      ])
rescue
  puts "Error saving users... continuing"
end

# ima = ImaApi.new('O4FK6qtxiu4m')
# i = 0
# limit = 100
# todas_escolas = []
# while true do
#   escolas = ima.escolas(i, 100)
#   break if escolas.length === 0
#   todas_escolas.concat escolas
#   # School.create(ima)
#   i += 1
# end
# p todas_escolas.length

e_length = 50
ima = ImaApi.new('O4FK6qtxiu4m')
ima_escolas = ima.escolas(0, e_length)
e_length.times do |i|
  School.create([{
                  ima_id: ima_escolas[i]['id']
                }])
end
